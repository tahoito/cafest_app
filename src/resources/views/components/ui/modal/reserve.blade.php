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
    x-show="reserveOpen"
    x-transition.opacity
    class="fixed inset-0 z-[999] flex items-end justify-center"
    style="display:none;"
    @keydown.escape.window="reserveOpen=false"
  >
    <div class="absolute inset-0 bg-black/40" @click="reserveOpen=false"></div>

    <div
      x-data="{
        days: [],
        selectedDate: '',
        startTime: '',
        endTime: '',
        people: '1',

        hours: {
          mon: { open: '10:00', close: '18:00' },
          tue: null, // 定休日
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
          const today = new Date();

          for (let i=0; i<14; i++) {
            const d = new Date(today);
            d.setDate(today.getDate() + i);

            const value = this.formatDate(d);
            const label = i === 0 ? '今日' : `${d.getMonth() + 1}/${d.getDate()}`;

            const key = this.getDowKey(value);
            const isClosed = this.hours[key] === null;

            this.days.push({
              label,
              value,
              status: isClosed ? 'full' : 'ok', // 火曜は×にする
            });
          }

          this.selectedDate = this.days[0]?.value || '';
          this.buildTimeOptions();
        },
      }"

      x-transition:enter="transition ease-out duration-200"
      x-transition:enter-start="translate-y-6 opacity-0"
      x-transition:enter-end="translate-y-0 opacity-100"
      x-transition:leave="transition ease-in duration-150"
      x-transition:leave-start="translate-y-0 opacity-100"
      x-transition:leave-end="translate-y-6 opacity-0"
      class="relative w-full max-w-md rounded-t-3xl bg-form shadow-[0_-10px_30px_rgba(0,0,0,0.25)]"
    >
      <div class="pt-3 pb-2 flex justify-center">
        <div class="h-1.5 w-10 rounded-full bg-black/10"></div>
      </div>

      <div class="bg-form px-5 pt-3 pb-4 rounded-t-3xl relative">
        <div class="mx-auto mb-2 h-1.5 w-12 rounded-full bg-line"></div>

        <button
            type="button"
            class="absolute left-4 top-1 grid h-10 w-10 place-items-center rounded-full hover:bg-black/5"
            @click="reserveOpen=false"
            aria-label="閉じる"
        >
            <x-icons.close class="w-8 h-8 text-text_color" />
        </button>
      </div>


      <form class="bg-base_color px-5 pt-4 pb-6 space-y-6" action="{{ $postTo }}" method="POST">
        @csrf
        <div class="space-y-2">
          <div class="text-text_color text-lg font-medium">
            日付 <span class="text-text_color text-sm">(2週間後までしか予約できません)</span>
          </div>

          <div class="grid grid-cols-7 gap-2 text-center">
            <template x-for="d in days" :key="d.value">
              <button
                type="button"
                class="rounded-lg border px-2.5 py-2 bg-base"
                :class="selectedDate === d.value ? 'border-main ring-2 ring-main/30' : 'border-black/10'"
                @click="if (d.status !== 'full') { selectedDate = d.value; buildTimeOptions(); }"
              >
                <div class="text-sm" x-text="d.label"></div>
                <div class="mt-1 text-lg leading-none">
                  <span x-show="d.status === 'ok'">○</span>
                  <span x-show="d.status === 'few'">△</span>
                  <span x-show="d.status === 'full'">×</span>
                </div>
              </button>
            </template>
          </div>

          <input type="hidden" name="date" :value="selectedDate" required>
        </div>

        <div class="space-y-2">
          <div class="text-text_color text-lg font-medium">時間</div>
          <div class="flex items-center gap-3 text-placeholder text-sm">
            <select name="start_time" x-model="startTime" @change="updateEndOptions()" required class="w-full rounded-xl bg-base px-4 py-3 ring-1 ring-black/10"
              :class="startTime ? 'text-text_color' : 'text-placeholder text-sm'">
              <option value="" disabled>開始</option>
              <template x-for="t in startOptions" :key="t">
                <option :value="t" x-text="t"></option>
              </template>
            </select>

            <div class="text-text_color">ー</div>

            <select name="end_time" x-model="endTime" required class="w-full rounded-xl bg-base px-4 py-3 ring-1 ring-black/10"
              :class="endTime ? 'text-text_color' : 'text-placeholder text-sm'">
              <option value="" disabled>終了</option>
              <template x-for="t in endOptions" :key="t">
                <option :value="t" x-text="t"></option>
              </template>
            </select>
          </div>
        </div>

        <div class="space-y-2">
          <div class="text-text_color text-lg font-medium">人数</div>
          <div class="flex flex-wrap gap-4">
            <template x-for="n in [
              {label:'1', value:1},
              {label:'2', value:2},
              {label:'3', value:3},
              {label:'4', value:4},
              {label:'5', value:5},
              {label:'6〜', value:6}
            ]" :key="n.label">
              <label class="flex items-center gap-2">
                <input type="radio" name="people" :value="n.value" x-model="people" class="h-5 w-5 accent-main">
                <span class="text-base" x-text="n.label + '名'"></span>
              </label>
            </template>
          </div>
        </div>

        <div class="flex justify-center pt-2">
          <x-ui.button :type="'submit'" variant="secondary" class="text-form">
            次へ
          </x-ui.button>
        </div>
      </form>
    </div>
  </div>
</template>
