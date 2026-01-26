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

    <div class="h-full overflow-y-auto overscroll-contain pt-[calc(env(safe-area-inset-top)+4rem)]">
        <div class="w-full px-4 mx-auto pt-5 space-y-6 pb-4">
            <section>
                <div class="flex justify-center mt-6">
                    <button type="button" class="px-5 py-2 text-base text-text_color bg-base_color border-r border-main"
                    >全期間
                    </button>
                    <button type="button" class="px-5 py-2 text-base text-form bg-main border-r border-main"
                    >週
                    </button>
                    <button type="button" class="px-5 py-2 text-base text-form bg-main border-r border-main"
                    >月
                    </button>
                    <button type="button" class="px-5 py-2 text-base text-form bg-main border-r border-main"
                    >日
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
                            先週より <span class="text-text_color">+10%</span>
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
            

            <section class="px-4 space-y-3">
                <div class="text-lg text-text_color font-medium">閲覧数の推移</div>
            </section>

        </div>
    </div>
  </div>
</div>
@endsection
