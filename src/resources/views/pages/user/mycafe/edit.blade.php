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

        <form method="POST" action="{{ route('user.mycafe.update') }}" enctype="multipart/form-data">
            @csrf 
  
            <div class="h-full overflow-y-auto overscroll-contain pt-[calc(env(safe-area-inset-top)+4rem)]">
                <div class="w-full max-w-md mx-auto pt-4 space-y-5 pb-24">
                    <section class="px-5 pt-4 space-y-6">

                        <div class="flex justify-center">
                            <div class="relative w-[260px] rounded-2xl border border-main p-3">
                                <div class="aspect-square rounded-xl overflow-hidden">
                                    <img
                                        id="avatarPreview"
                                        src="{{ $user->icon_path
                                            ? \Illuminate\Support\Facades\Storage::url($user->icon_path)
                                            : \Illuminate\Support\Facades\Storage::url('users/user1.jpg') }}"
                                        class="w-full h-full object-cover"
                                    />
                                </div>

                        
                                <label class="absolute top-3 right-3 z-10 grid place-items-center
                                    w-9 h-9 rounded-full bg-accent cursor-pointer">
                                    <input 
                                        type="file" 
                                        name="avatar" 
                                        class="hidden" 
                                        accept="image/*"
                                        onChange="
                                            const f = this.files?.[0]; 
                                            if (!f) return;
                                            const u = window.URL || window.webkitURL;
                                            const url = u.createObjectURL(f);
                                            document.getElementById('avatarPreview').src = url;
                                        ">
                                    <x-icons.add class="w-6 h-6 text-text_color" />
                                </label>
                            </div>
                        </div>


                        <div class="space-y-[42px]">
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

                    
                            <div class="pt-[50px]">
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