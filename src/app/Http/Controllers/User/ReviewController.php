<?php


namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Store;
use App\Models\Review;
use App\Models\Tag;
use App\Models\ReviewImage;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ReviewController extends Controller
{
    public function create(Store $store)
    {
        return view('pages.user.reviews.create', compact('store'));
    }

    public function store(Request $request, Store $store)
    {
        $data = $request->validate([
            'rating' => ['required', 'integer', 'min:1', 'max:5'],
            'body' => ['nullable', 'string', 'max:1000'],
            'tags' => ['nullable', 'string', 'max:100'],

            'images' => ['nullable', 'array', 'max:6'],
            'images.*' => ['image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
        ]);

        $review = Review::create([
            'store_id' => $store->id,
            'user_id' => $request->user()->id,
            'rating' => $data['rating'],
            'body' => $data['body'] ?? null,
        ]);

        if (!empty($data['tags'])) {
            $names = collect(explode(',', $data['tags']))
                ->map(fn ($s) => trim($s))
                ->filter()
                ->unique()
                ->take(6);

            $tagIds = $names->map(function ($name) {
                $slug = Str::slug($name, '-');
                if ($slug === '') $slug = 'tag-' . Str::random(8);

                $tag = Tag::firstOrCreate(
                    ['name' => $name],
                    ['slug' => $slug . '-' . Str::random(4)]
                );

                return $tag->id;
            })->all();

            $review->tags()->sync($tagIds);
        }

        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $idx => $file) {
                $path = $file->store('reviews', 'public');

                ReviewImage::create([
                    'review_id' => $review->id,
                    'path' => $path,
                    'sort' => $idx,
                ]);
            }
        }

        return redirect()
            ->route('user.stores.show', $store) // ← stores.show じゃなくて user. が多分必要
            ->with('success', 'レビューを投稿しました');
    }
}
