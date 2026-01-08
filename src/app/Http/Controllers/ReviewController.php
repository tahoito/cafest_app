<?php

namespace App\Http\Controllers;

use App\Models\Store;
use App\Models\Review;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function create(Store $store)
    {
        return view('reviews.create',compact('store'));
    }

    public function store(Request $request, Store $store)
    {
        $data = $request->validate([
            'rating' => ['required','integer','min:1','max:5'],
            'body' => ['nullable','string','max:1000'],
            'tags' => ['nullable','string','max:10'],
            'images' => ['nullable','array','max:6'],
            'images.*' => ['image','mimes:jpg,jpeg,png,webp','max:4096'],

        ]);

        Review::create([
            'store_id' => $store->id,
            'user_id' => $request->user()->id,
            'rating' => $data['rating'],
            'body' => $data['body'] ?? null,
        ]);

        if(!empty($data['tags'])){
            $names = collect(explode(',',$data['tags']))
                ->map(fn($s) => trim($s))
                ->filter()
                ->unique()
                ->take(6);
            
            $tagIds = [];
            foreach ($names as $name) {
                $slug = Str::slug($name,'-');
                if($slug==='') $slug = 'tag-'.Str::random(8);

                $tag = Tag::firstOrCreate(
                    ['name' => $name],
                    ['slug' => $slug.Str::random(4)]
                );

                $tagIds[] = $tag->id;
            }

            $review->tags()->sync($tagIds);
        }

        if ($request->hasFilter('images')){
            foreach ($request->filter('images') as $idx => $file){
                $path = $file->store('reviews','public');

                ReviewImage::create([
                    'review_id' => $review->id,
                    'path' => $path,
                    'sort' => $idx,
                ]);
            }
        }

        return redirect()->route('stores.show',$store)
            ->with('success','レビューを投稿しました');
    }
}
