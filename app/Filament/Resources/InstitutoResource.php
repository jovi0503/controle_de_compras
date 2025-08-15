<?php

namespace App\Filament\Resources;

use App\Filament\Resources\InstitutoResource\Pages;
use App\Filament\Resources\InstitutoResource\RelationManagers;
use App\Models\Instituto;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Notifications\Notification; 
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\QueryException; 
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class InstitutoResource extends Resource
{
    protected static ?string $model = Instituto::class;

    protected static ?string $navigationIcon = 'heroicon-o-building-library'; 
    protected static ?string $modelLabel = 'Instituto';
    protected static ?string $pluralModelLabel = 'Institutos';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255)
                    ->label('Nome do Instituto'),
                Forms\Components\Toggle::make('e_visivel')
                    ->required()
                    ->default(true) 
                    ->label('Ativo'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Nome do Instituto')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\IconColumn::make('e_visivel')
                    ->label('Ativo')
                    ->boolean(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Data de criação')
                    ->dateTime('d/m/Y') 
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime('d/m/Y') 
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                
                Tables\Actions\DeleteAction::make()
                    ->action(function ($record) {
                        try {
                            $record->delete();
                            Notification::make()
                                ->title('Instituto excluído com sucesso!')
                                ->success()
                                ->send();
                        } catch (QueryException $e) {
                            if ($e->getCode() === '23503') {
                                Notification::make()
                                    ->title('Exclusão Falhou!')
                                    ->body('Este instituto não pode ser excluído pois está sendo utilizado em compras ou coordenações.')
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
                                    ->body("Não foi possível excluir {$cantDeleteCount} instituto(s), pois estão em uso.")
                                    ->danger()
                                    ->send();
                            } else {
                                Notification::make()
                                    ->title('Institutos excluídos com sucesso!')
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
            'index' => Pages\ListInstitutos::route('/'),
            'create' => Pages\CreateInstituto::route('/create'),
            'edit' => Pages\EditInstituto::route('/{record}/edit'),
        ];
    }

    public static function canViewAny(): bool
    {
        return auth()->user()->is_admin;
    }
}