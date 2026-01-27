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
            <section class="space-y-3">
                <div class="flex justify-center item-start mt-6">
                    <a href="{{ route('store.history', ['range' => 'all', 'base' => $base ]) }}"
                        class="x-3 py-1 text-base border border-main border-r-0 rounded-xl
                        {{ $range==='all' ? 'bg-main text-form' : 'bg-base_color text-text_color' }}">
                        全期間
                    </a>
                    <a href="{{ route('store.history', ['range' => 'week', 'base' => $base ]) }}"
                        class="x-3 py-1 text-base border border-main border-r-0 rounded-xl
                        {{ $range==='week' ? 'bg-main text-form' : 'bg-base_color text-text_color' }}">
                        週
                    </a>
                    <a href="{{ route('store.history', ['range' => 'month', 'base' => $base ]) }}"
                        class="x-3 py-1 text-base border border-main border-r-0 rounded-xl
                        {{ $range==='month' ? 'bg-main text-form' : 'bg-base_color text-text_color' }}">
                        月
                    </a>
                    <a href="{{ route('store.history', ['range' => 'year', 'base' => $base ]) }}"
                        class="x-3 py-1 text-base border border-main border-r-0 rounded-xl
                        {{ $range==='year' ? 'bg-main text-form' : 'bg-base_color text-text_color' }}">
                        年
                    </a>
                </div>

                <div class="flex items-center justify-center gap-3 text-text_color">
                    <button type="button" class="p-1 rounded-full">
                    <x-icons.chevron-left size="15"/>
                    </button>

                    <div class="flex items-center gap-2 text-sm">
                    <span>2025/12/08〜2025/12/14</span>
                    </div>

                    <button type="button" class="p-1 rounded-full">
                    <x-icons.chevron-right size="15" />
                    </button>
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
                            100
                        </div>
                    </div>

                    <div class="text-left">
                        <div class="text-text_color text-xl">
                            先週より <span class="text-text_color">+6%</span>
                        </div>
                        <div class="mt-2 text-main2 text-sm leading-snug">
                            お気に入り率: <span class="text-main2">40%</span>
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
                            40
                        </div>
                    </div>

                    <div class="text-left">
                        <div class="text-text_color text-xl">
                            先週より <span class="text-text_color">+6%</span>
                        </div>
                        <div class="mt-2 text-main2 text-sm leading-snug">
                            お気に入り率: <span class="text-main2">40%</span>
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
    document.addEventListener('DOMContentLoaded', () => {
      const canvas = document.getElementById('viewsChart');
      if (!canvas) return;

      const labels = ['1','2','3','4','5','6','7'];
      const values = [10,5,50,8,80,20,100];

      const chart = new Chart(canvas, {
        type: 'line',
        data: {
          labels: labels,
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
          plugins: {
            legend: { display: false },
            tooltip: { enabled: true }
          },
          scales: {
            x: {
              grid: { display: false },
              ticks: { font: { size: 12 } }
            },
            y: {
              beginAtZero: true,
              ticks: { font: { size: 12 } }
            }
          }
        }
      });
    });
  </script>
@endpush
@endsection
