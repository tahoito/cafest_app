@extends('layouts.app')
@section('title','閲覧数一覧')

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
            閲覧数一覧
          </h1>

          <div></div>
        </div>
      </div>
    </header>

    @php 
        $base = request('base');
    @endphp

    <div class="h-full overflow-y-auto overscroll-contain pt-[calc(env(safe-area-inset-top)+4rem)]">
        <div class="w-full px-4 mx-auto space-y-6 pb-4">
            <section class="space-y-2">
                <div class="flex justify-start items-start mt-3">
                    <a href="{{ route('store.history', ['range' => 'all', 'base' => $base ]) }}"
                        class="px-3 py-1 text-base border border-main border-r-0 
                        {{ $range==='all' ? 'bg-main text-form' : 'bg-base_color text-text_color' }}">
                        全期間
                    </a>
                    <a href="{{ route('store.history', ['range' => 'week', 'base' => $base ]) }}"
                        class="px-3 py-1 text-base border border-main border-r-0 
                        {{ $range==='week' ? 'bg-main text-form' : 'bg-base_color text-text_color' }}">
                        週
                    </a>
                    <a href="{{ route('store.history', ['range' => 'month', 'base' => $base ]) }}"
                        class="px-3 py-1 text-base border border-main border-r-0 
                        {{ $range==='month' ? 'bg-main text-form' : 'bg-base_color text-text_color' }}">
                        月
                    </a>
                    <a href="{{ route('store.history', ['range' => 'year', 'base' => $base ]) }}"
                        class="px-3 py-1 text-base border border-main border
                        {{ $range==='year' ? 'bg-main text-form' : 'bg-base_color text-text_color' }}">
                        年
                    </a>
                </div>

                <div class="flex items-center justify-start gap-1 text-text_color">
                    <a href="{{ route('store.history', ['range'=>$range, 'base'=>$prevBase ]) }}"
                        class="p-1 rounded-full {{ $range==='all' ? 'pointer-events-none opacity-30' : '' }}">
                        <x-icons.chevron-left size="15"/>
                    </a>


                    <div class="flex items-center gap-2 text-sm">{{ $rangeText }}</div>

                    <a href="{{ route('store.history', ['range'=>$range, 'base'=>$nextBase ]) }}"
                        class="p-1 rounded-full {{ $range==='all' ? 'pointer-events-none opacity-30' : '' }}">
                        <x-icons.chevron-right size="15"/>
                    </a>
                </div>
            </section>


            <section class="bg-base_color border-2 border-main rounded-xl px-5 py-5 shadow-[0_2px_10px_rgba(0,0,0,0.12)]">
                <div class="grid grid-cols-[1fr_1fr] items-center gap-6">
                    <div>
                        <div class="flex items-center">
                            <x-icons.eyes size="30" class="text-text_color" />
                            <div class="text-text_color text-xl">閲覧数</div>
                        </div>

                        <div class="mt-2 text-[56px] text-center leading-none text-text_color">
                            {{ $views }}
                        </div>
                    </div>

                    <div class="text-left">
                        <div class="text-text_color text-xl">
                            先週より <span class="text-text_color">{{ $viewsDiffPct >= 0 ? '+' : '' }}{{ $viewsDiffPct }}%</span>
                        </div>
                        <div class="mt-2 text-main2 text-sm leading-snug">
                            閲覧数が増えていってます。
                        </div>
                    </div>
                </div>
            </section>

            <section class="bg-base_color border-2 border-main rounded-xl px-5 py-5 shadow-[0_2px_10px_rgba(0,0,0,0.12)]">
                <div class="grid grid-cols-[1fr_1fr] items-center gap-6">
                    <div>
                        <div class="flex items-center">
                            <x-icons.heart size="30" class="text-text_color" />
                            <div class="text-text_color text-xl">お気に入り</div>
                        </div>

                        <div class="mt-2 text-[56px] text-center leading-none text-text_color">
                            {{ $favs }}
                        </div>
                    </div>

                    <div class="text-left">
                        <div class="text-text_color text-xl">
                            先週より <span class="text-text_color">{{ $favsDiffPct >= 0 ? '+' : '' }}{{ $favsDiffPct }}%</span>
                        </div>
                        <div class="mt-2 text-main2 text-sm leading-snug">
                            お気に入り率: <span class="text-main2">{{ $favRate !== null ? $favRate.'%' : '-' }}</span>
                        </div>
                    </div>
                </div>
            </section>
            

            <section class="space-y-3">
                <div class="text-lg text-text_color font-medium">閲覧数の推移</div>

                <div>
                    <div class="h-[220px]">
                        <canvas id="viewsChart"></canvas>
                    </div>
                </div>
            </section>
        </div>
    </div>
  </div>
</div>

@push('scripts')
  <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
  <script>
  (() => {
    let chartInstance = null;

    const boot = () => {
      const canvas = document.getElementById('viewsChart');
      if (!canvas || typeof Chart === 'undefined') return;

      const labels = @json($chartLabels);
      const values = @json($chartValues);

      if (chartInstance) {
        chartInstance.destroy();
        chartInstance = null;
      }

      chartInstance = new Chart(canvas, {
        type: 'line',
        data: {
          labels,
          datasets: [{
            label: '閲覧数',
            data: values,
            tension: 0.35,
            pointRadius: 4,
            borderColor: '#46392A',
            backgroundColor: '#46392A',
            pointBackgroundColor: '#46392A',
            pointBorderColor: '#46392A',
            pointHoverRadius: 5,
            borderWidth: 2,
            fill: false,
          }]
        },
        options: {
          responsive: true,
          maintainAspectRatio: false,
          plugins: { legend: { display: false }, tooltip: { enabled: true } },
          scales: {
            x: { grid: { display: false }, ticks: { font: { size: 12 } } },
            y: {
              beginAtZero: true,
              ticks: {
                precision: 0,
                stepSize: 1,
                callback: (v) => Number(v).toFixed(0),
              }
            }
          }
        }
      });
    };

    document.addEventListener('DOMContentLoaded', boot);
    document.addEventListener('turbo:load', boot);
  })();
</script>
@endpush
@endsection
