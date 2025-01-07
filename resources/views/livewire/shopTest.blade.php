<div class="p-24">

    <div class="grid grid-cols-5 gap-2 place-items-center">
        @foreach ($products as $item)
            <div wire:key='product-{{ $item->id }}' >

                <div id="default-carousel" class="relative w-[17rem]" data-carousel="static">
                    <!-- Carousel wrapper -->
                    <div class="relative h-56 overflow-hidden md:h-[17rem]">
                        @foreach ($item->images as $images)
                            <a href="product/{{ $item->slug }}" class="hidden duration-700 ease-in-out" data-carousel-item>
                                <img src="{{ asset('storage/' . $images->image) }}" class="transition-transform duration-500 ease-in-out transform hover:scale-110 absolute block w-full -translate-x-1/2 -translate-y-1/2 top-1/2 left-1/2" alt="...">
                            </a>
                        @endforeach
                    </div>
                    <!-- Slider indicators -->
                    <div class="absolute z-30 flex space-x-3 -translate-x-1/2 bottom-5 left-1/2 rtl:space-x-reverse">
                        @for ($i = 0; $i < $item->images->count(); $i++)
                            <button type="button" class="w-3 h-3 rounded-full" aria-current="true" aria-label="Slide 1" data-carousel-slide-to="{{ $i }}"></button>
                        @endfor
                    </div>
                    <!-- Slider controls -->
                    @if (count($item->images)>1)
                    <button type="button" class="absolute top-0 z-30 flex items-center justify-center h-full px-4 cursor-pointer start-0 group focus:outline-none" data-carousel-prev>
                        <span class="inline-flex items-center justify-center w-10 h-10 rounded-full bg-white/30 ">
                            <svg class="w-4 h-4 text-white dark:text-gray-800 rtl:rotate-180" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 1 1 5l4 4"/>
                            </svg>
                            <span class="sr-only">Previous</span>
                        </span>
                    </button>
                    <button type="button" class="absolute top-0 z-30 flex items-center justify-center h-full px-4 cursor-pointer end-0 group focus:outline-none" data-carousel-next>
                        <span class="inline-flex items-center justify-center w-10 h-10 rounded-full bg-white/30 ">
                            <svg class="w-4 h-4 text-white dark:text-gray-800 rtl:rotate-180" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 9 4-4-4-4"/>
                            </svg>
                            <span class="sr-only">Next</span>
                        </span>
                    </button>
                    @endif
                </div>

                <p class="mt-3 text-sm">{{ $item->name }}</p>
                <p class="mt-3">${{ $item->price }} MXN</p>
            </div>
        @endforeach
    </div>

    @push('js')
        <script src="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.3.0/flowbite.min.js"></script>
    @endpush

</div>
