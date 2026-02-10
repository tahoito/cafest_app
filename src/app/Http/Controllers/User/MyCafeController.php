<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

use App\Models\Review;
use App\Models\ViewHistory;
use App\Models\FavoriteFolder;

class MyCafeController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::guard('user')->user();
        $userId = $user->id;

        $slideImages = fn ($q) => $q->where('type', 'slide')->orderBy('sort_order');

        $favoritesAll = $user->favorites()
            ->with(['slideImages' => $slideImages])
            ->orderByDesc('user_favorites.created_at')
            ->get();

        $folders = FavoriteFolder::where('user_id', $userId)
            ->with(['stores' => function($q) {
                $q->with(['slideImages' => fn ($sq) => $sq->where('type', 'slide')->orderBy('sort_order')])
                    ->orderByPivot('created_at', 'desc');
            }])
            ->get();


        $foldersPayload = $folders->map(function ($folder) {
            $stores4 = $folder->stores->take(4);

            $thumbUrls = $stores4 
                ->map(fn($s) => $s->card_image_url ?? null)
                ->filter()
                ->unique()
                ->values()
                ->all();

            return [
                'id' => $folder->id,
                'name' => $folder->name,
                'count' => $folder->stores->count(),
                'thumb_urls' => $thumbUrls,
            ];
        })->values();


        $allThumbs = $favoritesAll
            ->map(fn ($s) => $s->card_image_url ?? null )
            ->filter()
            ->take(4)
            ->values();

       
        $favIds = $favoritesAll->pluck('id')->all();

        $reviews = Review::where('user_id', $userId)
            ->whereHas('store', fn ($q) => $q->where('is_public', true))
            ->with([
                'store:id,name',
                'user:id,name,icon_path,handle',
            ])
            ->latest()
            ->get();


        $histories = ViewHistory::where('user_id', $userId)
            ->with(['store' => fn ($q) => $q
                ->withAvg('reviews', 'rating')
                ->with(['slideImages' => $slideImages])
            ])
            ->orderByDesc('viewed_at')
            ->limit(30)
            ->get();

        return view('pages.user.mycafe', compact(
            'user',
            'favoritesAll',
            'foldersPayload',
            'allThumbs',
            'favIds',
            'reviews',
            'histories',
        ));
    }

    public function edit(Request $request)
    {
        $user = Auth::guard('user')->user();

        return view('pages.user.mycafe.edit',compact('user'));
    }

    public function update(Request $request) 
    {
        $user = Auth::guard('user')->user();

        $validated = $request->validate([
            'username' => ['required', 'string', 'max:30'],
            'email' => ['required', 'email', 'max:225'],
            'icon' => ['nullable', 'image', 'max:4096'],
        ]);

        $user->name = $validated['username'];
        $user->email = $validated['email'];

        if ($request->hasFile('icon')) {

            // 古い画像を消す（public運用）
            if ($user->icon_path && str_starts_with($user->icon_path, '/images/users/')) {
                $old = public_path(ltrim($user->icon_path, '/'));
                if (is_file($old)) @unlink($old);
            }

            $file = $request->file('icon');

            // 拡張子（安全に）
            $ext = $file->getClientOriginalExtension();
            $ext = in_array(strtolower($ext), ['jpg','jpeg','png','webp']) ? strtolower($ext) : 'jpg';

            // ファイル名固定（上書きで管理がラク）
            $filename = 'user_'.$user->id.'.'.$ext;

            // 保存先
            $dir = public_path('images/users');
            if (!is_dir($dir)) mkdir($dir, 0755, true);

            $file->move($dir, $filename);

            // DBにはURLパスを入れる
            $user->icon_path = '/images/users/'.$filename;
        }


        $user->save();

        return redirect()->route('user.mycafe')->with('success','更新しました');
    }

    public function favoriteFolder($folder)
    {
        $user = Auth::guard('user')->user();
        $userId = $user->id;
        $slideImages = fn ($q) => $q->where('type', 'slide')->orderBy('sort_order');

        $folderId = null;
        $folderName = "";

        if ($folder === 'all') {
            $stores = $user->favorites()
                ->with(['slideImages' => $slideImages])
                ->orderByDesc('user_favorites.created_at')
                ->get();
            $title = 'すべての投稿';
        } else {
            $folderModel = FavoriteFolder::where('user_id', $userId)
                ->findOrFail((int) $folder);

            $folderId = $folderModel->id;
            $folderName = $folderModel->name; 

            $stores = $folderModel->stores()
                ->with(['slideImages' => $slideImages])
                ->orderByDesc('favorite_folders_store.created_at')
                ->get();
            $title = $folderModel->name;
        }

        $favIds = $stores->pluck('id')->all();

        return view('pages.user.mycafe.mycafe_favorites',compact('stores', 'favIds', 'title', 'folder','folderId','folderName'));
    }

    public function destroy(FavoriteFolder $folder)
    {
        $user = Auth::guard('user')->user();
        $userId = $user->id; 

        abort_unless($folder->user_id === $userId, 403);

        $folder->stores()->detach();
        $folder->delete();

        return redirect()->route('user.mycafe');
    }

    public function updateFavoriteFolder(Request $request, FavoriteFolder $folder) {
        $userId = auth('user')->id();

        if ($folder->name === 'お気に入り') abort(403);
        abort_unless($folder->user_id === $userId, 403);

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:50'],
        ]);

        $folder->update([
            'name' => $validated['name'],
        ]);

        return response()->json([
            'ok' => true,
            'id' => $folder->id,
            'name' => $folder->name,
        ]);
    }
}
