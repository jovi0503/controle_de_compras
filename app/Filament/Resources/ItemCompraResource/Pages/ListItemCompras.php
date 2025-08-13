<?php

namespace App\Filament\Resources\ItemCompraResource\Pages;

use App\Filament\Resources\ItemCompraResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListItemCompras extends ListRecords
{
    protected static string $resource = ItemCompraResource::class;

    protected function getHeaderActions(): array
    {
        return[];
    }
}
