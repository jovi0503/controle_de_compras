<?php

namespace App\Filament\Resources\CoordinationResource\Pages;

use App\Filament\Resources\CoordinationResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListCoordinations extends ListRecords
{
    protected static string $resource = CoordinationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
