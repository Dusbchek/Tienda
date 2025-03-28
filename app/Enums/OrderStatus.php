<?php

namespace App\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasIcon;
use Filament\Support\Contracts\HasLabel;

enum OrderStatus: string implements HasColor, HasIcon, HasLabel
{
    case New = 'Nueva';

    case Processing = 'En Proceso';

    case Shipped = 'Enviada';

    case Delivered = 'Entregada';

    case Cancelled = 'Cancelada';

    public function getLabel(): string
    {
        return match ($this) {
            self::New => 'Nueva',
            self::Processing => 'En Proceso',
            self::Shipped => 'Enviada',
            self::Delivered => 'Entregada',
            self::Cancelled => 'Cancelada',
        };
    }

    public function getColor(): string | array | null
    {
        return match ($this) {
            self::New => 'info',
            self::Processing => 'warning',
            self::Shipped, self::Delivered => 'success',
            self::Cancelled => 'danger',
        };
    }

    public function getIcon(): ?string
    {
        return match ($this) {
            self::New => 'heroicon-m-sparkles',
            self::Processing => 'heroicon-m-arrow-path',
            self::Shipped => 'heroicon-m-truck',
            self::Delivered => 'heroicon-m-check-badge',
            self::Cancelled => 'heroicon-m-x-circle',
        };
    }
}
