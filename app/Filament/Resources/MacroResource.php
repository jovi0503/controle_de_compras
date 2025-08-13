<?php

namespace App\Filament\Resources;

use App\Filament\Resources\MacroResource\Pages;
use App\Models\Macro;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class MacroResource extends Resource
{
    protected static ?string $model = Macro::class;

    
    protected static ?string $navigationIcon = 'heroicon-o-squares-2x2';
    protected static ?string $modelLabel = 'Macro';
    protected static ?string $pluralModelLabel = 'Macros';
    protected static ?string $navigationLabel = 'Macros';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255)
                    ->label('Nome do Macro'),
                Forms\Components\Toggle::make('e_visivel')
                    ->required()
                    ->default(true)
                    ->label('Visível'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Nome do Macro')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\IconColumn::make('e_visivel')
                    ->label('Visível')
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
            'index' => Pages\ListMacros::route('/'),
            'create' => Pages\CreateMacro::route('/create'),
            'edit' => Pages\EditMacro::route('/{record:uuid}/edit'),
        ];
    }    
}