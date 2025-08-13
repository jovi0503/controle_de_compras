<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ItemCompraResource\Pages;
use App\Filament\Resources\CompraResource;
use App\Models\Compra;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class ItemCompraResource extends Resource
{
    protected static ?string $model = Compra::class;

    protected static ?string $navigationIcon = 'heroicon-o-check-badge';
    protected static ?string $modelLabel = 'Aprovação de Compra';
    protected static ?string $pluralModelLabel = 'Aprovações de Compra';
    protected static ?string $navigationLabel = 'Aprovação de Compras';
    protected static ?int $navigationSort = 3;

    public static function form(Form $form): Form
    {
        return $form->schema([]);
    }

    public static function table(Table $table): Table
    {
        return $table
            
            ->columns([
                Tables\Columns\TextColumn::make('name')->label('Objeto')->searchable(),
                Tables\Columns\TextColumn::make('coordination.name')->label('Coordenação'),
                Tables\Columns\TextColumn::make('data')->date('d/m/Y')->sortable(),
                Tables\Columns\TextColumn::make('user.name')
                ->label('Solicitante')
                ->sortable(),
                Tables\Columns\TextColumn::make('efetivadoPor.name')
                ->label('Efetivado por')
                ->placeholder('Análise') 
                ->sortable(),
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'pendente' => 'warning',
                        'aprovada' => 'info',
                        'efetivada' => 'success',
                        default => 'gray',
                    }),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\Action::make('view_items')
                    ->label('Ver Itens')
                    ->icon('heroicon-o-eye')
                    ->color('secondary')
                    ->url(fn (Compra $record): string => CompraResource::getUrl('view', ['record' => $record])),

                Tables\Actions\Action::make('efetivar')
                    ->label('Efetivar')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->requiresConfirmation()
                    ->modalHeading('Efetivar Compra')
                    ->modalDescription('Você tem certeza que deseja efetivar esta compra? Esta ação não pode ser desfeita.')
                    ->action(function (Compra $record) {
                        $record->status = 'efetivada';
                        $record->efetivado_por_id = auth()->id();
                        $record->save();
                    })
                    ->visible(fn (Compra $record): bool => $record->status !== 'efetivada'),
            ]);
    }
    
    public static function getPages(): array
    {
        return [
            'index' => Pages\ListItemCompras::route('/'),
        ];
    }

    
    public static function canCreate(): bool
    {
        return false;
    }
}