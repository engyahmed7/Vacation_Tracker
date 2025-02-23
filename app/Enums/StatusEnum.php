<?php

namespace App\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasLabel;

enum StatusEnum: int implements HasLabel, HasColor
{
    case REJECTED = 0;
    case PENDING = 1;
    case APPROVED = 2;

    public function getLabel(): ?string
    {
        return match ($this) {
            self::REJECTED => 'Rejected',
            self::PENDING => 'Pending',
            self::APPROVED => 'Approved',
        };
    }

    public function getColor(): string|array|null
    {
        return match ($this) {
            self::REJECTED => 'danger',
            self::PENDING => 'warning',
            self::APPROVED => 'success',
        };
    }
}