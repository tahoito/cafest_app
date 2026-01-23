@extends('layouts.app')
@section('title','プロフィール編集')


@section('hideNavbar')
@endsection


@section('content')
<div class="h-screen bg-base_color">
    <div class="h-full overflow-y-auto">
        <header class="fixed top-0 inset-x-0 z-50 bg-base_color">
            <div class="pt-[env(safe-area-inset-top)]">
                <div class="grid grid-cols-[48px_1fr_48px] items-center px-4 h-16">
                    <a class="p-2" href="{{ route('user.mycafe') }}">
                        <x-icons.back class="w-5 h-5 text-text_color" />
                    </a>

                    <h1 class="text-center text-text_color text-2xl whitespace-nowrap overflow-hidden text-ellipsis">
                        プロフィール編集
                    </h1>
                </div>
            </div>
        </header>

        <form method="POST" action="{{ route('user.mycafe.update') }}">
            @csrf 
  
            <div class="h-full overflow-y-auto overscroll-contain pt-[calc(env(safe-area-inset-top)+4rem)]">
                <div class="w-full max-w-md mx-auto pt-4 space-y-5 pb-24">
                    <section class="px-5 pt-4 space-y-6">

                        <div class="flex justify-center">
                            <div class="relative w-[260px] rounded-2xl border border-line bg-form p-3">
                                <div class="aspect-square rounded-xl overflow-hidden">
                                    <img
                                        src="{{ $user->avatar_path ? Storage::url($user->avatar_path) }}"
                                        class="w-full h-full object-cover"
                                    >
                                </div>

                        
                                <a class="p-2" href="{{ url()->previous() }}">
                                    <x-icons.plus class="w-5 h-5 text-accent" />
                                </a>
                            </div>
                        </div>


                        <div class="space-y-10">
                            <div class="space-y-1">
                                <x-ui.label for="username">ユーザー名</x-ui.label>
                                <x-ui.input
                                    id="username"
                                    type="text"
                                    name="username"
                                    placeholder="ユーザー名を入力"
                                    value="{{ old('username', $user->name ?? '') }}"
                                    required
                                    autocomplete="off"
                                />
                            </div>

                
                            <div class="space-y-1">
                                <x-ui.label for="email">メールアドレス</x-ui.label>
                                <div class="relative">
                                    <x-ui.input
                                        id="email"
                                        type="email"
                                        name="email"
                                        placeholder="メールアドレスを入力"
                                        value="{{ old('email',$user->email ?? '') }}"
                                        autocomplete="new-password"
                                    />
                                </div>
                            </div>

                    
                            <div class="pt-6">
                                <x-ui.button type="submit" class="w-full">
                                    保存
                                </x-ui.button>
                            </div>
                        </div>
                    </section>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection