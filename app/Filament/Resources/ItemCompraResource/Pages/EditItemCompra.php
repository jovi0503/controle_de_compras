<?php

namespace App\Filament\Resources\ItemCompraResource\Pages;

use App\Filament\Resources\ItemCompraResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditItemCompra extends EditRecord
{
    protected static string $resource = ItemCompraResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
