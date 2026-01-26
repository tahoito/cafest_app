<div class="px-4">
    <div class="grid grid-cols-2 gap-3">
        @foreach($histories as $history)
            @php
                $store = $history->store;
            @endphp

            @if ($store)
            <x-ui.card.store :store="$store"
            :href="route('user.stores.show', ['store' => $store->id])"
            variant="grid"
            />
            @endif
        @endforeach
    </div>
</div>