<?php

namespace App\Filament\Resources\InstitutoResource\Pages;

use App\Filament\Resources\InstitutoResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListInstitutos extends ListRecords
{
    protected static string $resource = InstitutoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
