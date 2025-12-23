@extends('layouts.app')

@section('content')
    <x-ui.button class="w-[90%] mx-auto">ユーザーログイン</x-ui.button>
    <x-ui.button theme="store" class="w-[90%] mx-auto">サインアップ</x-ui.button>
    <x-ui.button variant="secondary" class="w-[70%] mx-auto">次へ</x-ui.button>
    <x-ui.button variant="next">戻る</x-ui.button>
    <x-ui.input type="text" placeholder="メールアドレスを入力"/>
    <div class="px-4 pt-4">
        <x-ui.search-bar />
    </div>
    <x-ui.chip variant="mood">韓国風</x-ui.chip>
    <x-ui.chip variant="area">栄</x-ui.chip>
@endsection

