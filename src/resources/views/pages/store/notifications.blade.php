@extends('layouts.app')
@section('title','通知')

@section('hideNavbar')
@endsection

@section('content')


<div class="h-screen bg-base_color">
    <div class="h-full overflow-y-auto">
        <header class="fixed top-0 inset-x-0 z-50 bg-base_color">
            <div class="pt-[env(safe-area-inset-top)]">
                <div class="grid grid-cols-[48px_1fr_48px] items-center px-4 h-16">
                    <a class="p-2" href="{{ route('store.top') }}">
                        <x-icons.back class="w-5 h-5 text-text_color" />
                    </a>

                    <h1 class="text-center text-text_color text-2xl whitespace-nowrap overflow-hidden text-ellipsis">
                        通知
                    </h1>
                </div>
            </div>
        </header>

        <div class="h-full overflow-y-auto overscroll-contain pt-[calc(env(safe-area-inset-top)+4rem)]">
            <div class="w-full max-w-md mx-auto pt-5 space-y-6 pb-4">
                
                @if ($notifications->isEmpty())
                <div class="text-sm flex items-center justify-center text-placeholder">
                    通知はまだありません
                </div>
                @else 
                <div class="space-y-3">
                    @foreach ($notifications as $notification)
                        @php 
                            $data = $notification->data;
                            $isUnread = is_null($notification->read_at);
                            $type = $data['type'] ?? '';
                        @endphp 

                        <form method="POST" action="{{ route('store.notifications.read', $notification->id) }}">
                            @csrf

                            <button type="submit" class="w-full text-left">
                                <div class="flex items-center gap-3 px-4 py-4">
                                {{-- 左：アイコン --}}
                                <div class="shrink-0">
                                    @if ($type === 'review.posted')
                                    <x-icons.review_notification class="w-10 h-10" />
                                    @elseif ($type === 'reservation.created')
                                    <x-icons.reserve_notification class="w-10 h-10" />
                                    @else
                                    <x-icons.bell class="w-10 h-10 text-[#201200]" />
                                    @endif
                                </div>

                                {{-- 中：本文 --}}
                                <div class="min-w-0 flex-1">
                                    <div class="text-sm text-text_color truncate">
                                    {{ $data['body'] ?? ($data['title'] ?? '通知') }}
                                    </div>
                                </div>

                                {{-- 右：未読ドット + 時間 --}}
                                <div class="shrink-0 flex flex-col items-end gap-2">
                                    @if ($isUnread)
                                    <span class="w-2.5 h-2.5 rounded-full bg-[color:var(--notification-color)]"></span>
                                    @else
                                    <span class="w-2.5 h-2.5"></span>
                                    @endif

                                    <div class="text-[11px] text-placeholder whitespace-nowrap">
                                    {{ $notification->created_at->diffForHumans() }}
                                    </div>
                                </div>
                                </div>

                                {{-- 下線（画像の区切り線っぽく） --}}
                                <div class="mx-4 border-b border-[color:var(--line-color)]"></div>
                            </button>
                            </form>

                    @endforeach
                </div>
                @endif 
            </div>
        </div>
    </div>
</div>
@endsection 