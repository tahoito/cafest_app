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

                        <form method="POST"
                            action="{{ route('store.notifications.read', $notification->id) }}">
                            @csrf 

                            <button type="submit" class="w-full text-left rounded-xl p-4 transition
                                {{ $isUnread ? 'bg-card-back' : 'bg-base_color' }}">
                                 
                                <div class="w-10 h-10 flex items-center justify-center">
                                    @if ($type === 'review.posted')
                                        <x-icons.review_notification />
                                    @elseif ($type === 'reservation.created')
                                        <x-icons.reserve_notification />
                                    @endif 
                                </div>
                                <div class="flex items-start gap-3">
                                    @if ($isUnread) 
                                        <span class="mt-2 w-[10px] h-[10px] rounded-full bg-notification"></span>
                                    @endif 

                                    <div class="flex-1 space-y-1">
                                        <div class="text-sm text-text_color">
                                            {{ $data['title'] ?? '通知' }}
                                        </div>

                                        <div class="text-sm text-text_color">
                                            {{ $data['body'] ?? '' }}
                                        </div>

                                        <div class="text-[11px] text-placeholder">
                                            {{ $notification->created_at->diffForHumans() }}
                                        </div>
                                    </div>
                                </div>
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