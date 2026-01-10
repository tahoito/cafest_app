@props([
  'store' => null,
  'action' => null,
])

@php
  $storeId = data_get($store, 'id');
  $postTo  = $action ?? ($storeId ? url("/user/stores/{$storeId}/reservations") : '#');
@endphp

<template x-teleport="body">
  <div
    {{ $attributes->merge(['class' => 'fixed inset-0 z-[200] flex items-end justify-center']) }}
    x-data="{
      open: false,              
      days: [],
      selectedDate: '',
      startTime: '',
      endTime: '',
      people: '1',

      hours: {
        mon: { open: '10:00', close: '18:00' },
        tue: null,
        wed: { open: '10:00', close: '18:00' },
        thu: { open: '10:00', close: '18:00' },
        fri: { open: '10:00', close: '18:00' },
        sat: { open: '09:00', close: '19:00' },
        sun: { open: '09:00', close: '19:00' },
      },

      startOptions: [],
      endOptions: [],

      formatDate(d) {
        const y = d.getFullYear();
        const m = String(d.getMonth() + 1).padStart(2, '0');
        const day = String(d.getDate()).padStart(2,'0');
        return `${y}-${m}-${day}`;
      },

      getDowKey(dateStr) {
        const d = new Date(dateStr + 'T00:00:00');
        const map = ['sun','mon','tue','wed','thu','fri','sat'];
        return map[d.getDay()];
      },

      toMinutes(t) {
        const [h,m] = t.split(':').map(Number);
        return h*60 + m;
      },

      toTime(min) {
        const h = String(Math.floor(min/60)).padStart(2,'0');
        const m = String(min%60).padStart(2,'0');
        return `${h}:${m}`;
      },

      buildTimeOptions() {
        this.startTime = '';
        this.endTime = '';
        this.startOptions = [];
        this.endOptions = [];

        if (!this.selectedDate) return;

        const key = this.getDowKey(this.selectedDate);
        const rule = this.hours[key];
        if (!rule) return;

        const open = this.toMinutes(rule.open);
        const close = this.toMinutes(rule.close);

        for (let t = open; t <= close - 30; t += 30) {
          this.startOptions.push(this.toTime(t));
        }
      },

      updateEndOptions() {
        this.endTime = '';
        this.endOptions = [];

        if (!this.selectedDate || !this.startTime) return;

        const key = this.getDowKey(this.selectedDate);
        const rule = this.hours[key];
        if (!rule) return;

        const start = this.toMinutes(this.startTime) + 30;
        const close = this.toMinutes(rule.close);

        for (let t = start; t <= close; t += 30) {
          this.endOptions.push(this.toTime(t));
        }
      },

      init() {
        this.days = [];
        this.selectedDate = '';
        this.startTime = '';
        this.endTime = '';
        this.people = '1';
        this.startOptions = [];
        this.endOptions = [];
        
        const today = new Date();
        const map = ['sun','mon','tue','wed','thu','fri','sat'];
        const jp  = ['日','月','火','水','木','金','土'];

        for (let i=0; i<14; i++) {
          const d = new Date(today);
          d.setDate(today.getDate() + i);

          const value = this.formatDate(d);
          const dowIndex = d.getDay();
          const dowKey = map[dowIndex];
          const dow = jp[dowIndex];

          const isClosed = this.hours[this.getDowKey(value)] === null;

          this.days.push({
            label: i === 0 ? '今日' : `${d.getMonth() + 1}/${d.getDate()}`,
            value,
            dow,
            dowKey,
            status: isClosed ? 'full' : 'ok',
          });
        }

        this.selectedDate = this.days[0]?.value || '';
        this.buildTimeOptions();

        this.$watch('startTime', () => this.updateEndOptions());
        this.$watch('selectedDate', () => this.buildTimeOptions());
      },
    }"

    x-modelable="open"
    x-init="init()"
    x-show="open"
    x-transition.opacity.duration.200ms
    class="fixed inset-0 z-[200] flex items-end justify-center"
    style="display:none;"
    @keydown.escape.window="open = false"
  >
    {{-- 背景 --}}
    <div class="absolute inset-0 bg-black/40" @click="open = false"></div>

    {{-- 本体 --}}
    <div
      x-transition:enter="transition ease-out duration-200"
      x-transition:enter-start="translate-y-6 opacity-0"
      x-transition:enter-end="translate-y-0 opacity-100"
      x-transition:leave="transition ease-in duration-150"
      x-transition:leave-start="translate-y-0 opacity-100"
      x-transition:leave-end="translate-y-6 opacity-0"
      class="relative w-full max-w-md rounded-t-3xl bg-form shadow-[0_-10px_30px_rgba(0,0,0,0.25)]"
      @click.stop
    >
      <div class="pt-3 pb-2 flex justify-center">
        <div class="h-1.5 w-10 rounded-full bg-black/10"></div>
      </div>

      <div class="bg-form px-5 pt-3 pb-4 rounded-t-3xl relative">
        <button
          type="button"
          class="absolute left-4 top-1 grid h-10 w-10 place-items-center rounded-full hover:bg-black/5 active:scale-95"
          @click="open = false"
          aria-label="閉じる"
        >
          <x-icons.close class="w-8 h-8 text-text_color" />
        </button>
      </div>

      <form class="bg-base_color px-5 pt-4 pb-6 space-y-6" action="{{ $postTo }}" method="POST">
        @csrf

        {{-- 日付 --}}
        <div class="space-y-2">
          <div class="text-text_color text-lg font-medium">
            日付 <span class="text-text_color text-sm">(2週間後までしか予約できません)</span>
          </div>

          <div class="-mx-5 px-5 overflow-x-auto">
            <div class="w-max">
              <div class="flex text-sm mb-1">
                <template x-for="d in days" :key="d.value">
                  <div class="w-[52px] text-center"
                    :class="d.dowKey === 'sat' ? 'text-[#0190CE]' : d.dowKey === 'sun' ? 'text-notification' : 'text-text_color'"
                    x-text="d.dow"
                  ></div>
                </template>
              </div>

              <div class="flex">
                <template x-for="d in days" :key="d.value">
                  <button
                    type="button"
                    class="min-w-[52px] h-[70px] border px-2 py-2 text-center transition"
                    :class="[
                      selectedDate === d.value ? 'bg-main text-form border-main' : 'bg-base text-text_color border-placeholder',
                      d.status === 'full' ? 'opacity-40 cursor-not-allowed' : 'hover:bg-main hover:text-form'
                    ].join(' ')"
                    @click="if (d.status !== 'full') { selectedDate = d.value }"
                  >
                    <div class="text-sm whitespace-nowrap" x-text="d.label"></div>
                    <div class="mt-2 text-lg leading-none flex justify-center">
                      <span x-show="d.status === 'ok'" class="text-current"><x-icons.ok /></span>
                      <span x-show="d.status === 'few'" class="text-current"><x-icons.triangle /></span>
                      <span x-show="d.status === 'full'" class="text-current"><x-icons.no /></span>
                    </div>
                  </button>
                </template>
              </div>
            </div>
          </div>

          <input type="hidden" name="date" :value="selectedDate" required>
        </div>

        {{-- 時間 --}}
        <div class="space-y-2">
          <div class="text-text_color text-lg font-medium">時間</div>

          <div class="flex items-center gap-3 text-sm">
            <select
              name="start_time"
              x-model="startTime"
              required
              class="w-full rounded-xl bg-base px-4 py-3 ring-1 ring-black/10"
              :class="startTime ? 'text-text_color' : 'text-placeholder'"
            >
              <option value="" disabled>開始</option>
              <template x-for="t in startOptions" :key="t">
                <option :value="t" x-text="t"></option>
              </template>
            </select>

            <div class="text-text_color">〜</div>

            <select
              name="end_time"
              x-model="endTime"
              required
              class="w-full rounded-xl bg-base px-4 py-3 ring-1 ring-black/10"
              :disabled="endOptions.length === 0"
              :class="endTime ? 'text-text_color' : 'text-placeholder'"
            >
              <option value="" disabled>終了</option>
              <template x-for="t in endOptions" :key="t">
                <option :value="t" x-text="t"></option>
              </template>
            </select>
          </div>
        </div>

        {{-- 人数 --}}
        <div class="space-y-2">
          <div class="text-text_color text-lg font-medium">人数</div>
          <div class="flex flex-wrap gap-4">
            <template x-for="n in [
              {label:'1', value:1},{label:'2', value:2},{label:'3', value:3},
              {label:'4', value:4},{label:'5', value:5},{label:'6〜', value:6}
            ]" :key="n.label">
              <label class="flex items-center gap-2">
                <input type="radio" name="people" :value="n.value" x-model="people" class="h-5 w-5 accent-main">
                <span class="text-base" x-text="n.label + '名'"></span>
              </label>
            </template>
          </div>
        </div>

        <div class="flex justify-center pt-2">
          <x-ui.button type="submit" variant="secondary" class="text-form">
            次へ
          </x-ui.button>
        </div>
      </form>
    </div>
  </div>
</template>
