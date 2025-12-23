@extends('layouts.app')
@section('title','トップ')

@section('content')
<div class="min-h-screen flex items-center justify-center bg-base_color">
	<div class="text-center">
		<h1 class="text-2xl">ようこそ、店舗トップページです</h1>
		<p class="mt-4">ログインに成功しました。</p>
		<div class="mt-6">
			<a href="{{ route('welcome') }}" class="text-main hover:underline">ホームへ戻る</a>
		</div>
	</div>
</div>
@endsection
