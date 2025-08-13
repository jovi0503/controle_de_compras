<?php

namespace App\Filament\Resources\CoordinationResource\Pages;

use App\Filament\Resources\CoordinationResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditCoordination extends EditRecord
{
    protected static string $resource = CoordinationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
