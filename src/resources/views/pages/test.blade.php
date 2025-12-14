@extends('layouts.app')

@section('content')
    <x-ui.button class="w-[90%] mx-auto">ユーザーログイン</x-ui.button>
    <x-ui.button theme="store" class="w-[90%] mx-auto">サインアップ</x-ui.button>
    <x-ui.button variant="secondary" class="w-[70%] mx-auto">次へ</x-ui.button>
    <x-ui.button variant="next">戻る</x-ui.button>
    <x-icons.user class="w-8 h-8 text-main"/>
    <x-ui.input type="text" placeholder="メールアドレスを入力"/>
@endsection

