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
            loop: true,
            autoplay: {
                delay: 5000,
                disableOnInteraction: false,
            },
            navigation: {
                nextEl: ".swiper-button-next",
                prevEl: ".swiper-button-prev",
            },
            breakpoints: {
                640: {
                    slidesPerView: 2,
                    spaceBetween: 20,
                },
                1180: {
                    slidesPerView: 3,
                    spaceBetween: 50,
                },
            },
        });
    </script>
@endpush

<section class="py-12">
    <header class="flex flex-col justify-center items-center py-12">
        <h2 class="font-extrabold text-3xl">Testimonios</h2>
    </header>

    <div>
        <div class="swiper item-carrusel">

            <div class="swiper-wrapper">
                <div class="swiper-slide">
                    <x-testimonialCard name="Ana Sánchez" rate="3">
                        “Estoy encantada con mi última compra en esta tienda. Las prendas son de alta calidad y siempre recibo muchos cumplidos cuando las uso. ¡Definitivamente volveré a comprar aquí!”
                    </x-testimonialCard>
                </div>
                <div class="swiper-slide">
                    <x-testimonialCard name="Marycarmen Ramírez" rate="3">
                        “La tienda tiene una gran variedad de estilos para elegir. Siempre encuentro algo que me gusta y que se ajusta perfectamente a mi estilo. ¡Las prendas son increíbles!”
                    </x-testimonialCard>
                </div>
                <div class="swiper-slide">
                    <x-testimonialCard name="Elena Torres" rate="3">
                        “Me encanta la rapidez con la que llegan mis pedidos. Además, las prendas siempre lucen exactamente como en las fotos de la página web. ¡Muy satisfecho con mi experiencia de compra!”
                    </x-testimonialCard>
                </div>
                <div class="swiper-slide">
                    <x-testimonialCard name="Melissa Villa" rate="3">
                        “Estoy encantada con mi última compra en esta tienda. Las prendas son de alta calidad y siempre recibo muchos cumplidos cuando las uso. ¡Definitivamente volveré a comprar aquí!”
                    </x-testimonialCard>
                </div>
                <div class="swiper-slide">
                    <x-testimonialCard name="Katya García" rate="3">
                        “La tienda tiene una gran variedad de estilos para elegir. Siempre encuentro algo que me gusta y que se ajusta perfectamente a mi estilo. ¡Las prendas son increíbles!”
                    </x-testimonialCard>
                </div>
                <div class="swiper-slide">
                    <x-testimonialCard name="Carmen Torres" rate="3">
                        “Me encanta la rapidez con la que llegan mis pedidos. Además, las prendas siempre lucen exactamente como en las fotos de la página web. ¡Muy satisfecho con mi experiencia de compra!”
                    </x-testimonialCard>
                </div>
            </div>

            <div class="swiper-button-next"></div>
            <div class="swiper-button-prev"></div>
        </div>
    </div>

</section>
