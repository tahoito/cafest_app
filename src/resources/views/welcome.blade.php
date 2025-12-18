@extends('layouts.app')
@section('title','welcomeページ')

@section('content')
<div class="min-h-screen flex items-center justify-center bg-base">
    <div class="w-full max-w-md px-4">

        <div class="mb-20 flex flex-col items-center">
            <h1 class="text-center text-text text-2xl">
                ようこそ
            </h1>
        </div>

        <div class="space-y-12 mb-15">
            <div class="space-y-4">
                <div class="flex items-center gap-3 mb-2 ml-4">
                    <x-icons.user size="40" stroke="2" class="text-main" />
                    <div class="text-2xl text-main font-medium">ユーザー</div>
                </div>
                <div class="space-y-5">
                    <div class="flex justify-center">
                        <x-ui.button class="w-full">
                            ログイン
                        </x-ui.button>
                    </div>
                    <div class="flex justify-center">
                        <x-ui.button type="submit" class="w-full">
                            サインアップ
                        </x-ui.button>
                    </div>
                </div>
            </div>

            <div class="space-y-4">
                <div class="flex items-center gap-3 mb-2 ml-4">
                    <x-icons.store size="40" stroke="1" class="text-main2" />
                    <div class="text-2xl text-main2 font-medium">店舗</div>
                </div>
                <div class="space-y-5">
                    <div class="flex justify-center">
                        <x-ui.button type="submit" theme="store" class="w-full">
                            ログイン
                        </x-ui.button>
                    </div>
                    <div class="flex justify-center">
                        <x-ui.button type="submit" theme="store" class="w-full">
                            サインアップ
                        </x-ui.button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>