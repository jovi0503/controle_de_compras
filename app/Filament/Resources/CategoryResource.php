<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CategoryResource\Pages;
use App\Models\Category;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Notifications\Notification; // Importado
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\QueryException; // Importado

class CategoryResource extends Resource
{
    protected static ?string $model = Category::class;

    protected static ?string $navigationIcon = 'heroicon-o-tag';
    protected static ?string $modelLabel = 'Categoria';
    protected static ?string $pluralModelLabel = 'Categorias';
    protected static ?string $navigationLabel = 'Categorias';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255)
                    ->label('Nome da Categoria'),
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
                    ->label('Nome da Categoria')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\IconColumn::make('e_visivel')
                    ->label('Ativo')
                    ->boolean(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Data de Criação')
                    ->dateTime('d/m/Y')
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
                                ->title('Categoria excluída com sucesso!')
                                ->success()
                                ->send();
                        } catch (QueryException $e) {
                            if ($e->getCode() === '23503') {
                                Notification::make()
                                    ->title('Exclusão Falhou!')
                                    ->body('Esta categoria não pode ser excluída pois está sendo utilizada em um ou mais materiais.')
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
                                    ->body("Não foi possível excluir {$cantDeleteCount} categoria(s), pois estão em uso por materiais.")
                                    ->danger()
                                    ->send();
                            } else {
                                Notification::make()
                                    ->title('Categorias excluídas com sucesso!')
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
            'index' => Pages\ListCategories::route('/'),
            'create' => Pages\CreateCategory::route('/create'),
            'edit' => Pages\EditCategory::route('/{record}/edit'),
        ];
    }

    public static function canViewAny(): bool
    {
        return auth()->user()->is_admin;
    }
}