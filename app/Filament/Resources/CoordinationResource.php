<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CoordinationResource\Pages;
use App\Filament\Resources\CoordinationResource\RelationManagers;
use App\Models\Coordination;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class CoordinationResource extends Resource
{
    protected static ?string $model = Coordination::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

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
                    ->label('Visível')
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
                    ->label('Visível')
                    ->boolean(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
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
            'index' => Pages\ListCoordinations::route('/'),
            'create' => Pages\CreateCoordination::route('/create'),
            'edit' => Pages\EditCoordination::route('/{record}/edit'),
        ];
    }
}
