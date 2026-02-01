<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Store;
use App\Models\Review;
use App\Models\Tag;
use App\Models\ReviewImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ReviewController extends Controller
{
    public function create(Store $store)
    {
        $slideImage = $store->slideImages()->first();

        $approvedTags = Tag::where('status', 'approved')
            ->orderBy('is_seed', 'desc')
            ->orderBy('name')
            ->get();

        return view('pages.user.reviews.create', compact('store', 'slideImage', 'approvedTags'));
    }

    public function store(Request $request, Store $store)
    {
        $data = $request->validate([
            'rating' => ['required', 'integer', 'min:1', 'max:5'],
            'body'   => ['nullable', 'string', 'max:1000'],
            'tags'   => ['nullable', 'string', 'max:100'],

            'images'   => ['nullable', 'array', 'max:8'],
            'images.*' => ['image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
        ]);

        // cafest が user guard の想定ならこっちが安全
        $user = auth('user')->user();
        abort_unless($user, 403);

        $review = Review::create([
            'store_id' => $store->id,
            'user_id'  => $user->id,
            'rating'   => $data['rating'],
            'body'     => $data['body'] ?? null,
        ]);

        // tags
        $this->syncTagsFromCsv($review, $data['tags'] ?? '', $user->id);

        // images
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $idx => $file) {
                $path = $file->store('reviews', 'public');

                ReviewImage::create([
                    'review_id' => $review->id,
                    'path'      => $path,
                    'sort'      => $idx,
                ]);
            }
        }

        return redirect()
            ->route('user.stores.show', $store)
            ->with('review_success', 'レビューを投稿しました');
    }

    public function edit(Store $store, Review $review)
    {
        // store と review が一致してるか（URL改ざん対策）
        abort_unless((int)$review->store_id === (int)$store->id, 404);

        abort_unless($review->user_id === auth('user')->id(), 403);

        $review->load(['tags', 'images', 'store', 'user']);

        $approvedTags = Tag::where('status', 'approved')
            ->orderBy('is_seed', 'desc')
            ->orderBy('name')
            ->get();

        return view('pages.user.reviews.edit', compact('review', 'store', 'approvedTags'));
    }

    public function update(Request $request, Store $store, Review $review)
    {
        abort_unless((int)$review->store_id === (int)$store->id, 404);
        abort_unless($review->user_id === auth('user')->id(), 403);

        $data = $request->validate([
            'rating' => ['required', 'integer', 'min:1', 'max:5'],
            'body'   => ['required', 'string', 'max:2000'],
            
            'tag_ids'   => ['nullable','array','max:8'],
            'tag_ids.*' => ['integer','exists:tags,id'],


            'delete_image_ids'   => ['nullable', 'array'],
            'delete_image_ids.*' => ['integer'],

            'images'   => ['nullable', 'array'],
            'images.*' => ['image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
        ]);

        $review->update([
            'rating' => $data['rating'],
            'body'   => $data['body'],
        ]);

       
        $tagIds = collect($data['tag_ids'] ?? [])->unique()->take(8)->values()->all();
        $review->tags()->sync($tagIds);

        // delete images
        $deleteIds = collect($data['delete_image_ids'] ?? [])->unique()->values();
        if ($deleteIds->isNotEmpty()) {
            $imgs = $review->images()->whereIn('id', $deleteIds)->get();
            foreach ($imgs as $img) {
                Storage::disk('public')->delete($img->path);
                $img->delete();
            }
        }

        // add images (max 8)
        $currentCount = $review->images()->count();
        $canAdd = max(0, 8 - $currentCount);

        if ($canAdd > 0 && $request->hasFile('images')) {
            $nextSort = (int)($review->images()->max('sort') ?? -1) + 1;

            foreach (collect($request->file('images'))->take($canAdd) as $file) {
                $path = $file->store('reviews', 'public');

                $review->images()->create([
                    'path' => $path,
                    'sort' => $nextSort++,
                ]);
            }
        }

        return redirect()
            ->route('user.stores.reviews.edit', [$store, $review])
            ->with('success', '更新したよ');
    }

    public function destroy(Store $store, Review $review)
    {
        abort_unless((int)$review->store_id === (int)$store->id, 404);
        abort_unless($review->user_id === auth('user')->id(), 403);

        $review->load('images');

        foreach ($review->images as $img) {
            Storage::disk('public')->delete($img->path);
            $img->delete();
        }

        $review->tags()->detach();
        $review->delete();

        return redirect()
            ->route('user.mycafe')
            ->with('success', '削除したよ');
    }
}
