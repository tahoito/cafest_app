<div class="px-4">
    <div class="px-4 flex flex-col items-center space-y-[30px] pt-5">
        @foreach($reviews as $review)
            @php
                $storeId  = data_get($review, 'store_id', data_get($review, 'store.id'));
                $reviewId = data_get($review, 'id');

                $editUrl = ($storeId && $reviewId)
                    ? route('user.stores.reviews.edit', ['store' => $storeId, 'review' => $reviewId])
                    : null;
            @endphp
            <x-ui.card.user.user-review :review="$review" :href="$editUrl"
                class="h-[196px]" />
        @endforeach
    </div>
</div>