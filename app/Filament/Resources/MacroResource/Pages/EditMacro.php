<?php

namespace App\Filament\Resources\MacroResource\Pages;

use App\Filament\Resources\MacroResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditMacro extends EditRecord
{
    protected static string $resource = MacroResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
