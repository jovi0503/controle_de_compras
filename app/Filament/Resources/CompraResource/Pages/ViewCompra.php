<?php

namespace App\Filament\Resources\CompraResource\Pages;

use App\Filament\Resources\CompraResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;


class ViewCompra extends ViewRecord
{
    protected static string $resource = CompraResource::class;

    protected function getHeaderActions(): array
{

    if ($this->record->status !== 'efetivada') {
        return [
            Actions\EditAction::make(),
        ];
    }

    
    return [];
}
}