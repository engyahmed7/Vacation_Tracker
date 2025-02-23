<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;

enum VacationTypesEnum : int implements HasLabel
{
    case CASUAL = 1;
    case ANNUAL = 2;
    
    public function getLabel(): ?string
    {
        return match($this)
        {
            self::CASUAL => 'Casual',
            self::ANNUAL => 'Annual',
        };
    }
}
