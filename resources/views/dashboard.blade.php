@push('js')
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
@endpush

@push('css')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
@endpush

<x-app-layout>

    {{-- Banner --}}
    <header class="w-full h-[95vh]">
        <div class="skeleton w-full h-[85vh] lg:h-[80vh]"></div>
    </header>

    {{-- Contenedor Principal --}}
    <article class="max-w-7xl mx-auto px-6 lg:px-1 overflow-hidden text-black">

        {{-- Nueva Colección --}}
        <section>

            <header class="flex flex-col justify-center items-center py-12">
                <h2 class="font-extrabold text-3xl">¡Descubre la Nueva Colección!</h2>
                <h4 class="text-lg font-medium mt-5 md:w-[36rem] text-center">
                    Estilos frescos y vibrantes te esperan. No te pierdas nuestras últimas tendencias y haz que tu
                    guardarropa brille esta temporada
                </h4>
            </header>

            @livewire('item-carrusel', ['products' => $products])

        </section>

        {{-- Colecciones --}}
        <x-landing-store.collections />

        {{-- Testimonios --}}
        <x-landing-store.testimonials />

        {{-- Sobre Nosotros --}}
        <x-landing-store.aboutUs />

    </article>
</x-app-layout>
