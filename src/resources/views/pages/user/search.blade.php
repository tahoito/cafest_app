@extend('layouts.app')
@section('content')

<div class="px-4 pt-4">
    <x-ui.search-bar />
</div>

<div class="mt-6">
    {{-- カフェリスト --}}
    <x-pages.user.cafelist :cafes="$cafes" />
</div>
@endsection