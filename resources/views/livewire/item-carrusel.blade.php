@push('css')
    <style>
        .swiper {
            width: 100%;
            height: 100%;
        }

        .swiper-slide {
            text-align: center;
            font-size: 18px;
            display: flex;
            justify-content: center;
            align-items: center;
            height: auto;
        }

        .swiper-slide img {
            display: block;
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
    </style>
@endpush

@push('js')
    <script>
        var swiper = new Swiper(".item-carrusel", {
            speed: 400,
            slidesPerView: 1,
            spaceBetween: 30,
            navigation: {
                nextEl: ".swiper-button-next",
                prevEl: ".swiper-button-prev",
            },
            pagination: {
                el: ".swiper-pagination",
                clickable: true,
            },
            breakpoints: {
                640: {
                    slidesPerView: 2,
                    spaceBetween: 20,
                },
                1180: {
                    slidesPerView: 3,
                    spaceBetween: 40,
                },
            },
        });
    </script>
@endpush

{{-- Carrusel de productos --}}
<div class="py-12">
    <div class="swiper item-carrusel">
        <div class="swiper-wrapper">
            @foreach ($products as $item)
                <div class="swiper-slide">
                    @livewire(
                        'ProductCard',
                        [
                            'id' => $item->id,
                            'name' => $item->name,
                            'description' => $item->description,
                            'price' => $item->price,
                            'colors' => $item->colors->map(function ($color) {
                                return (object) [
                                    'id' => $color->id,
                                    'color' => $color->color,
                                    'hexadecimal' => $color->hexadecimal,
                                ];
                            }),
                            'imagesCollection' => $item->images->map(function ($image) use ($item) {
                                return (object) [
                                    'color_id' => $image->colors_id,
                                    'image' => $image->image,
                                ];
                            }),
                            'slug' => $item->slug,
                        ],
                        key($item->name)
                    )
                </div>
            @endforeach
        </div>
        <div class="swiper-button-next"></div>
        <div class="swiper-button-prev"></div>
    </div>
</div>
