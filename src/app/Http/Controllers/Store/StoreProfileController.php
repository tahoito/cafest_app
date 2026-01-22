<?php

namespace App\Http\Controllers\Store;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\StoreSocialLink;
use App\Models\StoreHour;

class StoreProfileController extends Controller
{
    public function index()
    {
        $store = auth('store')->user();
        if (!$store) abort(403);

        $store = $store->fresh([
            'paymentMethods',
            'hours' => fn($q) => $q->orderBy('day_of_week'),
            'socialLinks',
        ]);

        $sns = $store->socialLinks->pluck('url', 'type')->toArray();
        $hasSns = count(array_filter($sns)) > 0;

        return view('pages.store.profile', compact('store', 'sns', 'hasSns'));
    }


    public function editBasic (Request $request) {
        $store = $request->user('store')->load(['hours','paymentMethods']);
        return view('pages.store.profile.edit-basic', compact('store'));
    }

    public function updateBasic (Request $request) {

        logger()->info('budget incoming', [
            'budget_range' => $request->input('budget_range'),
        ]);

        $store = $request->user('store');
        $validated = $request->validate([
            'name' => ['required','string','max:255'],
            'address' => ['required','string','max:255'],
            'area' => ['required', 'string'],
            'mood' => ['required', 'string'],
            'budget_range' => ['required','string'],
            'payments' => ['array'],
            'payments.*' => ['string'],
            'open_days' => ['array'],
            'open_days.*' => ['in:0,1,2,3,4,5,6'],
            'hours_mode' => ['nullable','in:same,byDay'],
            'is_24h' => ['nullable','in:0,1'],
            'same_open' => ['nullable','date_format:H:i'],
            'same_close' => ['nullable','date_format:H:i'],
            'hours' => ['array'],
            'hours.*.open' => ['nullable','date_format:H:i'],
            'hours.*.close' => ['nullable','date_format:H:i'],
        ]);

        $store->fill([
            'name' => $validated['name'],
            'address' => $validated['address'],
            'area' => $validated['area'],
            'mood' => $validated['mood'],
        ])->save();


        $range = (string)$validated['budget_range'];
        $nums = preg_split('/\D+/', $range, -1, PREG_SPLIT_NO_EMPTY);

        $budgetMin = isset($nums[0]) ? (int)$nums[0] : null;
        $budgetMax = isset($nums[1]) ? (int)$nums[1] : null;

        $store->fill([
            'name' => $validated['name'],
            'address' => $validated['address'],
            'area' => $validated['area'],
            'mood' => $validated['mood'],
            'budget_min' => $budgetMin,
            'budget_max' => $budgetMax,
        ])->save();



        $is24h = (bool)($request->input('is_24h') ?? false);
        $mode = $request->input('hours_mode','same');
        $openDays = collect($request->input('open_days',[]))
            ->map(fn($d) => (int)$d)
            ->all();
        for ($dow = 0; $dow <= 6; $dow++ ){
            $isOpen = in_array($dow, $openDays, true);
            $open = null;
            $close = null;

            if ($is24h && $isOpen) {
                $open = '00:00';
                $close = '23:59';
            }elseif($isOpen) {
                if ($mode === 'byDay') {
                    $open = $request->input("hours.$dow.open");
                    $close = $request->input("hours.$dow.close");
                }else {
                    $open = $request->input("same_open");
                    $close = $request->input("same_close");
                }
            }

            StoreHour::updateOrCreate (
                ['store_id' => $store->id, 'day_of_week' => $dow],
                [
                    'is_closed' => !$isOpen,
                    'open_time' => $open,
                    'close_time' => $close,
                ]
            );
        }

        $store->save();
        
        $slugs = $validated['payments'] ?? [];
        $ids = \App\Models\PaymentMethod::whereIn('slug', $slugs)->pluck('id')->all();
        $store->paymentMethods()->sync($ids);

        $store->forceFill(['basic_updated_at' => now()])->save();
        
        return redirect()->route('store.profile')->with('status', '基本情報を更新しました');
    }

    public function editDescription (Request $request) {
        $store = $request->user('store');
        return view('pages.store.profile.edit-description', compact('store'));
    }

    public function updateDescription (Request $request) {
        $store = $request->user('store');

        $validated = $request->validate([
            'description' => ['required','string','max:200'],
        ]);

        $store->update(['description' => $validated['description']]);
        $store->forceFill(['description_updated_at' => now()])->save();
        
        return redirect()->route('store.profile')->with('status', '店舗紹介を更新しました');
    }

    public function editContact (Request $request) {
        $store = $request->user('store')->load('socialLinks');
        $sns = $store->socialLinks->pluck('url','type')->toArray();
        return view('pages.store.profile.edit-contact', compact('store','sns'));
    }

    public function updateContact (Request $request) {
        $store = $request->user('store');

        $validated = $request->validate([
            'email' => ['required', 'email', 'max:225'],
            'phone' => ['required', 'string', 'max:30'],

            'sns' => ['array'],
            'sns.instagram' => ['nullable', 'url','max:2048'],
            'sns.tiktok' => ['nullable', 'url','max:2048'],
            'sns.x' => ['nullable', 'url','max:2048'],
            'sns.website' => ['nullable', 'url','max:2048'],
        ]);

        $store->fill([
            'email' => $validated['email'],
            'phone' => $validated['phone'],
        ])->save();

        $sns = $validated['sns'] ?? [];
        $allowedTypes = ['instagram', 'tiktok', 'x', 'website'];

        foreach ($allowedTypes as $type) {
            $url = $sns[$type] ?? null;
            if (filled($url)) {
                StoreSocialLink::updateOrCreate(
                    ['store_id' => $store->id, 'type' => $type],
                    ['url' => $url]
                );
            }else {
                StoreSocialLink::where('store_id', $store->id)
                ->where('type',$type)
                ->delete();
            }
        }
        $store->forceFill(['contact_updated_at' => now()])->save();
    
        return redirect()->route('store.profile')->with('status', '連絡情報を更新しました');
    }
    
}
