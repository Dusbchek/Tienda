<article class="px-20 py-24 relative">

    {{-- tienda --}}
    <section class="grid grid-cols-12 gap-5">

        <div class="col-span-12 lg:col-span-8 lg:col-start-4 pb-8">
            <p class="text-3xl font-black">{{ $filterTitle }}</p>
        </div>

        {{-- filtro mobile --}}

        <div>
            <button
                class="absolute right-12 top-6 min-[1024px]:hidden px-6 py-3 text-base font-thin hover:font-bold text-white bg-[#2447A9] hover:bg-[#223F86] focus:ring-4 focus:outline-none focus:ring-blue-400 rounded-lg text-center"
                onclick="filters.showModal()"><x-heroicon-o-funnel class="w-7 h-7 text-white" /></button>
        </div>
        <dialog id="filters" class="modal modal-bottom sm:modal-middle">
            <div class="modal-box">
                <h3 class="text-lg font-bold">Filtros</h3>
                {{-- products filter --}}
                <div class="max-w-full mt-8">

                    {{-- precio --}}
                    <div class="mb-8">
                        <p class="text-lg font-extrabold mb-2">Precio</p>
                        <div class="flex space-x-4">
                            {{-- precio mínimo --}}
                            <div>
                                <input type="number" placeholder="Precio mínimo" x-model="$wire.minPriceFilter"
                                    class="input rouded-full input-xs max-w-xs py-2 drop-shadow-md" />
                                @error('minPriceFilter')
                                    <p class="text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- precio máximo --}}
                            <div>
                                <input type="number" placeholder="Precio máximo" x-model="$wire.maxPriceFilter"
                                    class="input rouded-full input-xs max-w-xs py-2 drop-shadow-md" />
                                @error('maxPriceFilter')
                                    <p class="text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <x-filter-checkbox title="Talla" filterModel="sizesFilter" :items="$sizes->map(fn($size) => ['id' => $size->id, 'name' => $size->size])->toArray()" :selectedItems="$sizesFilter" />
                    <x-filter-checkbox title="Color" filterModel="colorsFilter" :items="$colors->map(fn($color) => ['id' => $color->id, 'name' => $color->color])->toArray()" :selectedItems="$colorsFilter" />
                    <x-filter-checkbox title="Categoría" filterModel="categoriesFilter" :items="$categories
                        ->map(fn($category) => ['id' => $category->id, 'name' => $category->category])
                        ->toArray()"
                        :selectedItems="$categoriesFilter" />
                </div>
                <div class="modal-action">
                    <form method="dialog">
                        <!-- if there is a button in form, it will close the modal -->
                        <button wire:click.="applyFilter"
                        class="btn btn-success text-white">
                        Aplicar
                    </button>
                    </form>
                </div>
            </div>
        </dialog>


        {{-- filtro pc --}}
        <aside class="hidden lg:block lg:col-span-3 overflow-y-auto max-h-screen">

            <header class="flex items-center space-x-6">
                <x-heroicon-o-funnel class="w-7 h-7 text-gray-800" />
                <span class="text-2xl font-extrabold">Filtros</span>
            </header>

            {{-- products filter --}}
            <div class="max-w-full mt-8">

                {{-- precio --}}
                <div class="mb-8">
                    <p class="text-lg font-extrabold mb-2">Precio</p>
                    <div class="flex md:flex-col xl:flex-row xl:space-x-4 md:space-y-4 xl:space-y-0">
                        {{-- precio mínimo --}}
                        <div>
                            <input type="number" placeholder="Precio mínimo" x-model="$wire.minPriceFilter"
                                class="input rouded-full input-xs max-w-xs py-2 drop-shadow-md" />
                            @error('minPriceFilter')
                                <p class="text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- precio máximo --}}
                        <div>
                            <input type="number" placeholder="Precio máximo" x-model="$wire.maxPriceFilter"
                                class="input rouded-full input-xs max-w-xs py-2 drop-shadow-md" />
                            @error('maxPriceFilter')
                                <p class="text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <x-filter-checkbox title="Talla" filterModel="sizesFilter" :items="$sizes->map(fn($size) => ['id' => $size->id, 'name' => $size->size])->toArray()" :selectedItems="$sizesFilter" />
                <x-filter-checkbox title="Color" filterModel="colorsFilter" :items="$colors->map(fn($color) => ['id' => $color->id, 'name' => $color->color])->toArray()" :selectedItems="$colorsFilter" />
                <x-filter-checkbox title="Categoría" filterModel="categoriesFilter" :items="$categories
                    ->map(fn($category) => ['id' => $category->id, 'name' => $category->category])
                    ->toArray()"
                    :selectedItems="$categoriesFilter" />

                <button type="button" wire:click="applyFilter"
                    class="px-6 py-3.5 w-2/3 text-base font-thin hover:font-bold text-white bg-[#2447A9] hover:bg-[#223F86] focus:ring-4 focus:outline-none focus:ring-blue-400 rounded-lg text-center">
                    Aplicar
                </button>
            </div>

        </aside>

        {{-- productos --}}
        <div class="grid grid-cols-1 min-[720px]:grid-cols-2 xl:grid-cols-3 gap-5 col-span-12 lg:col-span-9">
            @forelse ($products as $item)
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
            @empty
                <p class="text-center">No hay productos disponibles</p>
            @endforelse
        </div>

    </section>

</article>
