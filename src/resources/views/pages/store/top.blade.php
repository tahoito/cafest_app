@extends('layouts.app')
@section('title','トップ')

@section('content')
@section('hideNavbar')
@endsection

<div class="min-h-screen flex items-center justify-center bg-base_color">
	<div class="items-left text-text_color text-2xl">
		{{ $store->name }}
	</div>

	<label class="inline-flex items-center cursor-pointer">
		<input type="checkbox" class="sr-only" 
			@checked(auth('store')->user()->is_public)
			x-on:change="
				fetch('{{ route('store.toggle-public') }}',{
					method: 'POST',
					headers: {
						'X-CSRF-TOKEN' : '{{ csrf_token() }}',
						'Content-Type' : 'application/json'
					},
					body : JSON.stringify({
						is_public: $event.target.checked
					})
				})"
		>
		<div class="w-11 h-6 bg-gray-300 rounded-full relative transition
              after:content-[''] after:absolute after:top-0.5 after:left-0.5
              after:w-5 after:h-5 after:bg-white after:rounded-full after:transition
              peer-checked:bg-main peer-checked:after:translate-x-5">
  		</div>
	</label>

	<div>
		<div class="text-base text-text_color">
			店舗ページ公開
		</div>

		<p class="text-sm text-text_color mt-1">
		{{ auth('store')->user()->is_public ? '（公開中）' : '（非公開）' }}
		</p>
	</div>

	<div class="grid grid-cols-2 gap-4">
		<x-ui.top-item href="#" label="店舗情報">
			<x-slot name="icon">
				<x-icons.info size="60" />
			</x-slot>
		</x-ui.top-item>

		<x-ui.top-item href="#" label="公式写真">
			<x-slot name="icon">
				<x-icons.store_image size="60" />
			</x-slot>
		</x-ui.top-item>

		<x-ui.top-item href="#" label="メニュー管理" >	
			<x-slot name="icon">
				<x-icons.menu size="60" />
			</x-slot>
		</x-ui.top-item>

		<x-ui.top-item href="#" label="閲覧数一覧" >	
			<x-slot name="icon">
				<x-icons.graph size="60" />
			</x-slot>
		</x-ui.top-item>

		<x-ui.top-item href="#" label="レビュー覧" >	
			<x-slot name="icon">
				<x-icons.review size="60" stroke="1" />
			</x-slot>
		</x-ui.top-item>

		<x-ui.top-item href="#" label="予約状況" >	
			<x-slot name="icon">
				<x-icons.phone size="60" />
			</x-slot>
		</x-ui.top-item>
	</div>
</div>
@endsection
