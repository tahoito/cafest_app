@extends('layouts.app')
@section('title','通知')

@section('hideNavbar')
@endsection

@section('content')

@php
  use Carbon\Carbon;

  $groups = [
    'today' => ['label' => '今日', 'items' => collect()],
    'this_week' => ['label' => '今週', 'items' => collect()],
    'last_month' => ['label' => '先月', 'items' => collect()],
    'older' => ['label' => '以前', 'items' => collect()],
  ];

  foreach ($notifications as $n) {
    $created = $n->created_at;
    if ($created && $created->isToday()) {
      $groups['today']['items']->push($n);
    } elseif ($created && $created->isCurrentWeek()) {
      $groups['this_week']['items']->push($n);
    } elseif ($created && $created->isLastMonth()) {
      $groups['last_month']['items']->push($n);
    } else {
      $groups['older']['items']->push($n);
    }
  }
@endphp

<div class="h-screen bg-[#fbf7f3]">
  <div class="h-full overflow-y-auto">
    <header class="fixed top-0 inset-x-0 z-50 bg-[#fbf7f3]">
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
      <div class="w-full max-w-md mx-auto pt-5 pb-8">
        @if ($notifications->isEmpty())
          <div class="text-sm flex items-center justify-center text-placeholder">
            通知はまだありません
          </div>
        @else
          <div class="space-y-6 px-4">
            @foreach ($groups as $group)
              @if ($group['items']->isNotEmpty())
                <div class="space-y-3">
                  <div class="text-base text-placeholder">
                    {{ $group['label'] }}
                  </div>

                  <div class="rounded-2xl overflow-hidden bg-[#f4eee8] shadow-[0_1px_6px_rgba(0,0,0,0.08)]">
                    @foreach ($group['items'] as $notification)
                      @php
                        $data = $notification->data;
                        $isUnread = is_null($notification->read_at);
                        $type = $data['type'] ?? '';
                        $body = $data['body'] ?? ($data['title'] ?? '通知');
                      @endphp

                      <form method="POST" action="{{ route('store.notifications.read', $notification->id) }}" class="border-b border-main2 last:border-b-0">
                        @csrf
                        <button type="submit" class="w-full text-left">
                          <div class="flex items-center gap-4 px-4 py-4">
                            <div class="shrink-0">
                              <div class="w-12 h-12 rounded-2xl bg-main2 border border-main2 grid place-items-center">
                                @if ($type === 'review.posted')
                                  <x-icons.review class="w-6 h-6 text-main2" />
                                @elseif ($type === 'reservation.created')
                                  <x-icons.reserve class="w-6 h-6 text-main2" />
                                @else
                                  <x-icons.bell class="w-6 h-6 text-main2" />
                                @endif
                              </div>
                            </div>

                            <div class="min-w-0 flex-1">
                              <div class="text-sm text-[#2f241c] leading-relaxed line-clamp-2">
                                {{ $body }}
                              </div>
                            </div>

                            <div class="shrink-0 flex flex-col items-end gap-1">
                              @if ($isUnread)
                                <span class="w-2.5 h-2.5 rounded-full bg-[#ff4d4f]"></span>
                              @else
                                <span class="w-2.5 h-2.5"></span>
                              @endif
                              <div class="text-[11px] text-[#8a7b70] whitespace-nowrap">
                                {{ $notification->created_at->diffForHumans() }}
                              </div>
                            </div>
                          </div>
                        </button>
                      </form>
                    @endforeach
                  </div>
                </div>
              @endif
            @endforeach
          </div>
        @endif
      </div>
    </div>
  </div>
</div>
@endsection
