<div class="px-56 py-8" x-data>
    <p x-text="$wire.measures"></p>
    <div class="grid grid-cols-3 gap-8">
        <div class="col-span-2">
            <img :src="'../storage/' + $wire.image" class="w-[45rem] mask mask-squircle mt-[-150px] mb-[-160px] shadow-md transition-transform duration-500 ease-in-out transform hover:scale-105" alt="a">
        </div>
        <div>
            <div class="mb-3">
                <p class="text-xs font-mono text-gray-500 mb-2">Leshas Show Room</p>
                <h2 class="text-5xl mb-2">{{ $product->name }}</h2>
                <p class="text-lg">${{ $product->price }}.00 MXN</p>
                <p class="text-xs text-gray-500 mb-2">Impuestos incluídos</p>
            </div>
            <div>
                <p>{!! $product->description !!}</p>
            </div>
            <div>
                <p class="text-sm text-gray-500 mt-5">Color</p>
                @foreach ($product->colors as $color)
                    <button
                        :class="{ 'text-white bg-gray-800 hover:cursor-default': {{ $color->id }} == $wire.color }"
                        class="text-gray-900 border border-gray-800 hover:text-white hover:bg-gray-900 focus:ring-4 focus:outline-none focus:ring-gray-300 font-medium rounded-full text-sm px-5 py-2.5 text-center me-2 mb-2"
                        x-on:click="setColor({{ $color->id }})">
                        {{ $color->color }}
                    </button>
                @endforeach
                <p class="text-sm text-gray-500 mt-5">Tallas</p>
                @foreach ($product->sizes as $size)
                    <button
                        :class="{ 'text-white bg-gray-800 hover:cursor-default': {{ $size->id }} == $wire.size }"
                        class="text-gray-900 border border-gray-800 hover:text-white hover:bg-gray-900 focus:ring-4 focus:outline-none focus:ring-gray-300 font-medium rounded-full text-sm px-5 py-2.5 text-center me-2 mb-2"
                        x-on:click="setSize({{ $size->id }})">
                        {{ $size->size }}
                    </button>
                @endforeach
                <br>
                <p class="mt-5 underline">
                    <span class="text-lg hover:cursor-pointer hover:font-bold"
                        x-on:click="$wire.$toggle('openModal')">Guía de tallas</span>
                </p>


                <p class="text-sm text-gray-500 mt-5">Cantidad</p>
                <div class="relative flex items-center max-w-[11rem]">
                    <button x-on:click="$wire.amount--" type="button" id="decrement-button"
                        class="bg-gray-100 hover:bg-gray-200 border border-gray-300 rounded-s-lg p-3 h-11 focus:ring-gray-100 focus:ring-2 focus:outline-none">
                        <svg class="w-3 h-3 text-gray-900" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                            fill="none" viewBox="0 0 18 2">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M1 1h16" />
                        </svg>
                    </button>
                    <input x-model="$wire.amount" type="text" id="bedrooms-input"
                        class="bg-gray-50 border-x-0 border-gray-300 h-11 font-medium text-center text-gray-900 text-sm focus:ring-blue-500 focus:border-blue-500 block w-full pb-6" />
                    <div
                        class="absolute bottom-1 start-1/2 -translate-x-1/2 rtl:translate-x-1/2 flex items-center text-xs text-gray-400 space-x-1 rtl:space-x-reverse">
                        <span>Cantidad</span>
                    </div>
                    <button x-on:click="$wire.amount++" type="button" id="increment-button"
                        class="bg-gray-100 hover:bg-gray-200 border border-gray-300 rounded-e-lg p-3 h-11 focus:ring-gray-100 focus:ring-2 focus:outline-none">
                        <svg class="w-3 h-3 text-gray-900" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                            fill="none" viewBox="0 0 18 18">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 1v16M1 9h16" />
                        </svg>
                    </button>
                </div>

                <div>
                  
                    </div>
                    <div>

                        @if (session()->has('message'))
                            <div class="alert alert-success mt-5">
                                {{ session('message') }}
                            </div>
                        @endif

                        @if (session()->has('error'))
                            <div class="alert alert-danger">
                                {{ session('error') }}
                            </div>
                        @endif

                        <button wire:click="addToCart()" class= " mt-4 text-gray-900 border border-gray-800 hover:text-white hover:bg-gray-900 focus:ring-4 focus:outline-none focus:ring-gray-300 font-medium rounded-full text-sm px-5 py-2.5 text-center me-2 mb-2">
                            Agregar al carrito
                        </button>

                    </div>

                </div>
            </div>
        </div>

        <x-dialog-modal wire:model='openModal'>
            <x-slot name='title'>

                <p>Tabla de tallas</p>

                <button type="button"
                    class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center"
                    x-on:click="$wire.$toggle('openModal')">

                    <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                        viewBox="0 0 14 14">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                    </svg>
                    <span class="sr-only">Close modal</span>

                </button>
            </x-slot>

            <x-slot name='content'>
                <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
                    <table class="w-full text-sm text-left rtl:text-right text-gray-500">
                        <thead class="text-xs text-gray-700 uppercase">
                            <tr>
                                <th scope="col" class="px-6 py-3 bg-gray-50">
                                    Parte
                                </th>
                                @foreach ($sizes as $size)
                                    <th scope="col" class="px-6 py-3 bg-gray-50">
                                        {{ $size->size }}
                                    </th>
                                @endforeach
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($parts as $part => $measures)
                                <tr class="border-b border-gray-200">
                                    <th scope="row"
                                        class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap bg-gray-50">
                                        {{ $part }}
                                    </th>
                                    @foreach ($sizes as $size)
                                        <td class="px-6 py-4">
                                            {{ $measures[$size->size] ?? '-' }}
                                        </td>
                                    @endforeach
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

            </x-slot>

            <x-slot name='footer'>
                <x-button class="mr-5" x-on:click="$wire.$toggle('openModal')">
                    Cancelar
                </x-button>
            </x-slot>

        </x-dialog-modal>

        @push('js')
            <script>
                const images = @json($images);

                function setColor(color_id) {
                    @this.set('image', images.find(image => image.colors_id == color_id).image);
                    @this.set('color', color_id);

                    let params = new URLSearchParams(window.location.search);
                    params.set('variante', color_id);
                    history.pushState({}, '', '?' + params.toString());
                }

                function setSize(size_id) {
                    @this.set('size', size_id);

                    let params = new URLSearchParams(window.location.search);
                    params.set('talla', size_id);
                    history.pushState({}, '', '?' + params.toString());
                }
            </script>
        @endpush
    </div>
