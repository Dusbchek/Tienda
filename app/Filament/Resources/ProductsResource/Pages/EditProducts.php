<?php

namespace App\Filament\Resources\ProductsResource\Pages;

use App\Filament\Resources\ProductsResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class EditProducts extends EditRecord
{
    protected static string $resource = ProductsResource::class;

    /**
     * Maneja la actualización de un registro de producto.
     *
     * @param Model $record El registro actual del producto que se va a actualizar.
     * @param array $data Los datos actualizados del registro actual del producto.
     *
     * @return Model El registro actualizado del producto.
     */
    protected function handleRecordUpdate(Model $record, array $data): Model
    {
        /**
         * Obtiene los ID's de colores y las tallas del registro actual.
         *
         * @return Collection La colleción de IDs de colores del registro actual.
         */
        $currentColors = $record->colors->pluck('id');
        $currentSizes = $record->sizes->pluck('id');

        /**
         * Actualiza el registro del producto que los datos proporcionados.
         *
         * @param array $data Los datos actualizados del registro actual del producto.
         */
        $record->update($data);

        /**
         * Recupera las imágenes que no coincidan con 'colors_id' en el registro actual de colores.
         * Recupera las tallas y colores que no coincidan con sus respectivos id en las tabla de apollo del registro actual
         *
         * @return Collection La colección de imágenes que no coincidieron con 'colors_id'.
         */
        $deletedImages = $record->images()->whereNotIn('colors_id', $currentColors)->get();
        $deletedMeasures = $record->measures()->whereNotIn('sizes_id', $currentSizes)->get();
        $deletedColors = $record->stock()->whereNotIn('colors_id', $currentColors)->get();
        $deletedsizes = $record->stock()->whereNotIn('sizes_id', $currentSizes)->get();

        /**
         * Si la colección de imágenes a eliminar no está vacía
         */
        if (!$deletedImages->isEmpty())
        {
            /**
             * Elimina los registros de las imágenes relacionadas al recurso actual de la base de datos que coinciden con el id de la
             * colección recuperada de las imágenes.
             *
             * @param Collection $deletedImages La colección de imágenes a eliminar de la base de datos.
             */
            $record->images()->whereIn('id', $deletedImages->pluck('id'))->delete();


            /**
             * Elimina las imágenes del almacenamiento que coinciden en nombre con 'image' en la colección recuperada.
             *
             * @param Collection $deletedImages La colección de imágenes a eliminar de la base de datos.
             */
            Storage::delete($deletedImages->pluck('image')->toArray());

            /**
             * Envía el evento 'refreshRelation' para refrescar los datos de la relación.
             */
            $this->dispatch('refreshRelation');
        }

        if(!$deletedMeasures->isEmpty())
        {
            /**
             * Elimina los registros de las medidas relacionadas al recurso actual de la base de datos que coinciden con el id de la
             * colección recuperada de las tallas.
             *
             * @param Collection $deletedMeasures La colección de tallas a eliminar en la base de datos.
             */
            $record->measures()->whereIn('sizes_id', $deletedMeasures->pluck('sizes_id'))->delete();

            /**
             * Envía el evento 'refreshRelation' para refrescar los datos de la relación.
             */
            $this->dispatch('refreshRelation');
        }

        /**
         * Si la colección de colores a eliminar no está vacía
         */
        if(!$deletedColors->isEmpty())
        {
            /**
             * Elimina los registros del stock relacionadas al recurso actual de la base de datos que coinciden con el id de la
             * colección recuperada de los colores.
             *
             * @param Collection $deletedColors La colección de colores a eliminar en la base de datos.
             */
            $record->stock()->whereIn('colors_id', $deletedColors->pluck('colors_id'))->delete();

            /**
             * Envía el evento 'refreshRelation' para refrescar los datos de la relación.
             */
            $this->dispatch('refreshRelation');
        }

        /**
         * Si la colección de tallas a eliminar no está vacía
         */
        if(!$deletedsizes->isEmpty())
        {
            /**
             * Elimina los registros del stock relacionadas al recurso actual de la base de datos que coinciden con el id de la
             * colección recuperada de las tallas
             */
            $record->stock()->whereIn('sizes_id', $deletedsizes->pluck('sizes_id'))->delete();

            /**
             * Envía el evento 'refreshRelation' para refrescar los datos de la relación.
             */
            $this->dispatch('refreshRelation');
        }

        /**
         * Retorna los datos actualizados del registro actual del producto.
         *
         * @return Model The updated product record.
         */
        return $record;
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make()->label('Eliminar'),
        ];
    }
}
