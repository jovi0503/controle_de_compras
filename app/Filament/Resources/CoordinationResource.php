<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CoordinationResource\Pages;
use App\Filament\Resources\CoordinationResource\RelationManagers;
use App\Models\Coordination;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Notifications\Notification; // Importado
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\QueryException; // Importado
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class CoordinationResource extends Resource
{
    protected static ?string $model = Coordination::class;

    protected static ?string $navigationIcon = 'heroicon-o-user-group'; // Ícone alterado para um mais apropriado
    protected static ?string $modelLabel = 'Coordenação';
    protected static ?string $pluralModelLabel = 'Coordenações';
    protected static ?string $navigationLabel = 'Coordenações';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('instituto_id')
                    ->relationship('instituto', 'name')
                    ->searchable()
                    ->preload()
                    ->label('Instituto')
                    ->required(),
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->label('Nome da Coordenação')
                    ->maxLength(255),
                Forms\Components\Toggle::make('e_visivel')
                    ->label('Ativo')
                    ->default(true) // Adicionado um valor padrão
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Nome da Coordenação')
                    ->searchable(),
                Tables\Columns\TextColumn::make('instituto.name')
                    ->label('Instituto')
                    ->sortable(),
                Tables\Columns\IconColumn::make('e_visivel')
                    ->label('Ativo')
                    ->boolean(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime('d/m/Y') // Formato de data ajustado
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime('d/m/Y') // Formato de data ajustado
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                // AÇÃO DE EXCLUSÃO CUSTOMIZADA ADICIONADA AQUI
                Tables\Actions\DeleteAction::make()
                    ->action(function ($record) {
                        try {
                            $record->delete();
                            Notification::make()
                                ->title('Coordenação excluída com sucesso!')
                                ->success()
                                ->send();
                        } catch (QueryException $e) {
                            if ($e->getCode() === '23503') {
                                Notification::make()
                                    ->title('Exclusão Falhou!')
                                    ->body('Esta coordenação não pode ser excluída pois está sendo utilizada em uma ou mais compras.')
                                    ->danger()
                                    ->send();
                            } else {
                                throw $e;
                            }
                        }
                    }),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()
                        ->action(function ($records) {
                            $cantDeleteCount = 0;
                            foreach ($records as $record) {
                                try {
                                    $record->delete();
                                } catch (QueryException $e) {
                                    if ($e->getCode() === '23503') {
                                        $cantDeleteCount++;
                                    } else {
                                        throw $e;
                                    }
                                }
                            }
                            if ($cantDeleteCount > 0) {
                                Notification::make()
                                    ->title('Exclusão Parcialmente Falhou!')
                                    ->body("Não foi possível excluir {$cantDeleteCount} coordenação(ões), pois estão em uso.")
                                    ->danger()
                                    ->send();
                            } else {
                                Notification::make()
                                    ->title('Coordenações excluídas com sucesso!')
                                    ->success()
                                    ->send();
                            }
                        }),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCoordinations::route('/'),
            'create' => Pages\CreateCoordination::route('/create'),
            'edit' => Pages\EditCoordination::route('/{record}/edit'),
        ];
    }

    public static function canViewAny(): bool
    {
        return auth()->user()->is_admin;
    }
}