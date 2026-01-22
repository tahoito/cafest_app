<div class="px-4">
    @if ($favorites->isEmpty())
    <div class="text-placeholder text-sm">
        お気に入りはまだありません
    </div>
    @else
    <div class="grid grid-cols-2 gap-3">
        @foreach ($favorites as $store)
        <x-ui.card.store
            :store="$store"
            :faved="in_array(data_get($store,'id'), $favIds)"
            :href="route('user.stores.show', ['store' => data_get($store,'id')])"
            variant="grid"
        />
        @endforeach
    </div>
    @endif
</div>