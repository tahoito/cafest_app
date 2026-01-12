@extends('layouts.app')
@section('title','トップ')

@section('content')
@section('hideNavbar')
@endsection

<div class="min-h-screen flex items-center justify-center bg-base_color">
	<div class="items-left text-text_color text-2xl">
		
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
