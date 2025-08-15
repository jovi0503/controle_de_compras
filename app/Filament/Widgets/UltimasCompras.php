<?php

namespace App\Filament\Widgets;

use App\Models\Compra;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class UltimasCompras extends BaseWidget
{
    protected int | string | array $columnSpan = 'full'; 

    public function table(Table $table): Table
    {
        return $table
            
            ->heading('Últimas Solicitações de Compra')
            
            ->query(
                Compra::query()->latest()->limit(5)
            )
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Objeto da Compra'),
                Tables\Columns\TextColumn::make('user.name')
                    ->label('Solicitante'),
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                    'pendente' => 'warning',
                    'aprovada' => 'info',
                    'efetivada' => 'success',
                    default => 'gray',
    }),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Data')
                    ->since(),
            ]);
    }
}