<?php

namespace App\Traits;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Collection;

enum CacheType: string
{
    case CART = 'cart';
    case FAVORITES = 'favorites';

    public function getDisplayName(): string
    {
        return match ($this) {
            self::CART => 'carrito',
            self::FAVORITES => 'favoritos',
        };
    }
}

trait ProductCache
{
    /**
     * Obtiene y genera la cache key
     *
     * @param CacheType $cacheType
     * @return string
     */
    private function getCacheKey(CacheType $cacheType): string
    {
        return $cacheType->value . '_' . Auth::id();
    }

    /**
     * Obtiene los datos del cache
     *
     * @param CacheType $cacheType
     * @return Collection
     */
    private function getCacheData(CacheType $cacheType): Collection
    {
        $cacheKey = $this->getCacheKey($cacheType);
        return collect(json_decode(cache()->get($cacheKey, '[]'), true));
    }

    /**
     * Setea los datos al cache
     *
     * @param CacheType $cacheType
     * @param Collection $data
     * @return void
     */
    private function setCacheData(CacheType $cacheType, Collection $data): void
    {
        $cacheKey = $this->getCacheKey($cacheType);
        cache()->forever($cacheKey, $data->toJson());
    }

    /**
     * Elimina los datos de un producto del cache
     *
     * @param CacheType $cacheType
     * @param string $id
     * @return void
     */
    private function removeFromCache(CacheType $cacheType, string $id): void
    {
        $data = $this->getCacheData($cacheType);
        $columnName = $cacheType === CacheType::CART ? 'cart_id' : 'product_id';

        $updatedData = $data->reject(function ($item) use ($id, $columnName) {
            return $item[$columnName] === $id;
        })->values();

        $this->setCacheData($cacheType, $updatedData);

        if ($updatedData->count() < $data->count()) {
            session()->flash('message', 'Producto eliminado de ' . $cacheType->getDisplayName() . ' con éxito.');
        } else {
            session()->flash('error', 'No se pudo eliminar el producto de ' . $cacheType->getDisplayName() . '.');
        }
    }

    /**
     * Añade un producto a la cache
     *
     * @param CacheType $cacheType
     * @param integer $productId
     * @param integer|null $color
     * @param integer|null $size
     * @param integer|null $amount
     * @return void
     */
    private function addToCache(CacheType $cacheType, int $productId, ?int $color = null, ?int $size = null, ?int $amount = null): void
    {
        if ($productId === null) {
            session()->flash('error', 'Escoja una configuración válida para el producto');
            return;
        }

        if ($cacheType === CacheType::CART && ($size === null || $amount === null)) {
            session()->flash('error', 'Hubo un problema al agregar el producto a ' . $cacheType->getDisplayName() . '.');
            return;
        }

        $item = $this->createCacheItem($cacheType, $productId, $color, $size, $amount);
        $data = $this->getCacheData($cacheType);

        if ($cacheType === CacheType::CART) {
            $data = $this->updateOrAddToCache($data, $item);
        } else {
            $data->push($item);
        }

        $this->setCacheData($cacheType, $data);

        session()->flash('message', 'Producto agregado a ' . $cacheType->getDisplayName() . ' con éxito.');
    }

    /**
     * Crea el elemento para ponerlo en la cache
     *
     * @param CacheType $cacheType
     * @param integer $productId
     * @param integer|null $color
     * @param integer|null $size
     * @param integer|null $amount
     * @return array
     */
    private function createCacheItem(CacheType $cacheType, int $productId, ?int $color, ?int $size, ?int $amount): array
    {
        $item = [
            $cacheType->value . '_id' => uniqid(),
            'product_id' => $productId,
            'user_id' => Auth::id(),
        ];

        if ($color !== null) $item['color'] = $color;
        if ($size !== null) $item['size'] = $size;
        if ($amount !== null) $item['amount'] = $amount;

        return $item;
    }

    /**
     * actualiza o añade a la cache
     *
     * @param Collection $data
     * @param array $newItem
     * @return Collection
     */
    private function updateOrAddToCache(Collection $data, array $newItem): Collection
    {
        $found = false;
        $data = $data->transform(function ($item) use ($newItem, &$found) {
            if (
                $item['product_id'] === $newItem['product_id']
                && $item['color'] === $newItem['color']
                && $item['size'] === $newItem['size']
            ) {
                $item['amount'] += $newItem['amount'];
                $found = true;
            }
            return $item;
        });

        if (!$found) {
            $data->push($newItem);
        }

        return $data;
    }
}
