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
            
            'tag_ids'   => ['nullable','array','max:8'],
            'tag_ids.*' => ['integer','exists:tags,id'],

            // 新規タグ（JSで hidden new_tags[] を増やす）
            'new_tags'   => ['nullable','array','max:8'],
            'new_tags.*' => ['string','max:30'],


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

        $tagIds = $this->buildTagIds($data);
        $review->tags()->sync($tagIds);
    
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

        $allTagsForView = $approvedTags
            ->merge($review->tags)     
            ->unique('id')
            ->values();

        return view('pages.user.reviews.edit', [
            'review' => $review,
            'store' => $store, 
            'approvedTags' => $allTagsForView
        ]);
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

            'new_tags'   => ['nullable','array','max:8'],
            'new_tags.*' => ['string','max:30'],

            'delete_image_ids'   => ['nullable', 'array'],
            'delete_image_ids.*' => ['integer'],

            'images'   => ['nullable', 'array'],
            'images.*' => ['image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
        ]);

        $review->update([
            'rating' => $data['rating'],
            'body'   => $data['body'],
        ]);
       
        $tagIds = $this->buildTagIds($data);
        $review->tags()->sync($tagIds);


        $deleteIds = collect($data['delete_image_ids'] ?? [])->unique()->values();
        if ($deleteIds->isNotEmpty()) {
            $imgs = $review->images()->whereIn('id', $deleteIds)->get();
            foreach ($imgs as $img) {
                Storage::disk('public')->delete($img->path);
                $img->delete();
            }
        }

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
            ->route('user.mycafe', ['tab' => 'review'])
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
            ->route('user.mycafe', ['tab' => 'review'])
            ->with('success', '削除したよ');
    }

    private function buildTagIds(array $data): array
    {
        $selected = collect($data['tag_ids'] ?? [])
            ->filter()
            ->map(fn($id) => (int)$id)
            ->unique();

        $newNames = collect($data['new_tags'] ?? [])
            ->map(fn($s) => trim($s))
            ->filter()
            ->unique();

        if ($newNames->isNotEmpty()) {
            $created = $newNames->map(function ($name) {
                $slug = Str::slug($name, '-');
                if ($slug === '') $slug = 'tag-' . Str::random(8);

                $tag = Tag::firstOrCreate(
                    ['name' => $name],
                    [
                        'slug' => $slug . '-' . Str::random(4),
                        'is_seed' => false,
                        'status' => 'pending',
                    ]
                );

                return (int)$tag->id;
            });

            $selected = $selected->merge($created);
        }

        return $selected->unique()->take(8)->values()->all();
    }

}
