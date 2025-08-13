<?php

namespace App\Filament\Resources\InstitutoResource\Pages;

use App\Filament\Resources\InstitutoResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditInstituto extends EditRecord
{
    protected static string $resource = InstitutoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
