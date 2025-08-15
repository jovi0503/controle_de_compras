<?php

namespace App\Filament\Resources;

use App\Filament\Resources\MaterialResource\Pages;
use App\Filament\Resources\MaterialResource\RelationManagers;
use App\Models\Material;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\QueryException;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class MaterialResource extends Resource
{
    protected static ?string $model = Material::class;
    
    protected static ?string $modelLabel = 'Materiais';
    
    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('category_id')
                    ->relationship('category', 'name')
                    ->required(),
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255),
                Forms\Components\Textarea::make('descricao')
                    ->columnSpanFull(),
                Forms\Components\TextInput::make('cemat')
                    ->maxLength(255),
                Forms\Components\TextInput::make('simpas')
                    ->maxLength(255),
                Forms\Components\FileUpload::make('imagem')
                    ->label('Imagem do Material')
                    ->image()
                    ->directory('materials'),
                Forms\Components\Toggle::make('e_visivel')
                    ->label('Ativo')
                    ->required(),
            ]);
    }
    
    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Nome')
                    ->searchable(),
                Tables\Columns\TextColumn::make('category.name')
                    ->numeric()
                    ->label('Categoria')
                    ->sortable(),
                Tables\Columns\TextColumn::make('cemat')
                    ->searchable(),
                Tables\Columns\TextColumn::make('simpas')
                    ->searchable(),
                Tables\Columns\ImageColumn::make('imagem'), 
                Tables\Columns\IconColumn::make('e_visivel')
                    ->label('Ativo')
                    ->boolean(),
                Tables\Columns\TextColumn::make('created_at')
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
                                ->title('Material excluído com sucesso!')
                                ->success()
                                ->send();
                        } catch (QueryException $e) {
                            if ($e->getCode() === '23503') {
                                Notification::make()
                                    ->title('Exclusão Falhou!')
                                    ->body('Este material não pode ser excluído pois está sendo utilizado em uma ou mais compras.')
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
                    Tables\Actions\DeleteBulkAction::make(),
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
            'index' => Pages\ListMaterials::route('/'),
            'create' => Pages\CreateMaterial::route('/create'),
            'edit' => Pages\EditMaterial::route('/{record}/edit'),
        ];
    }
    
    public static function canViewAny(): bool
    {
        return auth()->user()->is_admin;
    }
}