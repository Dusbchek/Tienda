<div class="" @mouseleave="stopRotation;" x-data="{
    collection: $wire.imagesCollection,
    images: Object.values($wire.selectedImages),
    currentIndex: 0,

    interval: null,
    startRotation() {
        this.interval = setInterval(() => {
            this.currentIndex = (this.currentIndex + 1) % this.images.length;
        }, 1000); // Cambia cada 3 segundos
    },
    stopRotation() {
        clearInterval(this.interval);
        interval = null;
    }
}">
    <div class="relative" @mouseover="startRotation" @mouseleave="stopRotation; currentIndex = 0">
        <figure>

            <a href="product/{{$slug}}">
            <img  class="rounded-md h-[22rem] md:h-[30rem] w-full md:w-full transition-transform duration-500 ease-in-out transform hover:scale-105 shadow-lg "
            :src="'../storage/' + images[currentIndex].image" alt="">
            {{-- <div class="skeleton h-[22rem] md:h-[30rem] w-1/2 md:w-full"></div> --}}</a>
        </figure>
        <div class="absolute top-5 left-5 flex flex-col gap-5">
            <figure wire:click='handleFavorite' class="transform transition-transform duration-300 ease-out active:scale-75 hover:cursor-pointer">
                @if ($this->getFavorite())
                    <x-heroicon-s-heart class="text-gray-800" />
                @else
                    <x-heroicon-o-heart class="text-gray-800" />
                @endif
            </figure>
            <figure>
                <img src="https://img.icons8.com/?size=25&id=85028&format=png&color=000000" alt="">
            </figure>
        </div>
    </div>
    <div class="py-3 flex flex-col justify-center items-center">
        <h4 class="text-center text-xl font-medium">{{ $name }}</h4>
        <h4 class="text-center text-md">{{ $description }}</h4>
        <p class="pt-5 text-2xl font-black">
            ${{ number_format($price, 2, '.', '') == floor($price) ? number_format($price, 2, '.', '') : number_format($price, 2, '.', '') }}
        </p>

        <div class="pt-3">
            @foreach ($colors as $color)
                <div class="tooltip" data-tip="{{ $color->color }}">
                    <button style="background-color: {{ $color->hexadecimal }}"
                        class="btn w-[3rem] h-[3rem] rounded-[999px] focus:outline-none focus:ring-2 border border-slate-300"
                        x-on:click="if($wire.selectedColor != {{ $color->id }})
                            {
                                $wire.selectedColor = {{ $color->id }};
                                images = collection.filter(image => image.color_id === {{ $color->id }});
                            }"
                    >
                    </button>
                </div>
            @endforeach
        </div>
    </div>
</div>
