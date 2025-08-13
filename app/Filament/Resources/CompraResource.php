<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CompraResource\Pages;
use App\Models\Compra;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Number;
use Illuminate\Database\Eloquent\Builder;

class CompraResource extends Resource
{
    protected static ?string $model = Compra::class;

    protected static ?string $navigationIcon = 'heroicon-o-shopping-cart';
    protected static ?string $modelLabel = 'Gestão de Compra';
    protected static ?string $pluralModelLabel = 'Gestão de Compras';
    protected static ?string $navigationLabel = 'Gestão de Compras';
    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Dados da Solicitação')
                    ->schema([
                        Forms\Components\TextInput::make('name')->required()->label('Objeto da Compra')->columnSpanFull(),
                        Forms\Components\Select::make('instituto_id')->relationship('instituto', 'name')->required()->label('Instituto'),
                        Forms\Components\Select::make('coordination_id')->relationship('coordination', 'name')->required()->label('Coordenação'),
                        Forms\Components\Select::make('macro_id')->relationship('macro', 'name')->required(),
                        Forms\Components\TextInput::make('exercicio')->numeric()->required()->default(date('Y')),
                        Forms\Components\DatePicker::make('data')->required()->default('now'),
                    ])->columns(2),

                Forms\Components\Section::make('Itens da Compra')
                    ->schema([
                        Forms\Components\Repeater::make('items')
                            ->relationship()
                            ->schema([
                                Forms\Components\Select::make('material_id')
                                    ->relationship('material', 'name')
                                    ->searchable()->preload()->required()->label('Material')->columnSpan(4),
                                Forms\Components\TextInput::make('quantidade')
                                    ->numeric()->required()->default(1)->minValue(1)
                                    ->live(onBlur: true)
                                    ->afterStateUpdated(fn (Get $get, Set $set) => self::updateTotals($get, $set)),
                                Forms\Components\TextInput::make('valor_unitario')
                                    ->numeric()->required()->prefix('R$')->step('0.01')
                                    ->live(onBlur: true)
                                    ->afterStateUpdated(fn (Get $get, Set $set) => self::updateTotals($get, $set)),
                                Forms\Components\TextInput::make('valor_total')
                                    ->numeric()->prefix('R$')
                                    ->label('Subtotal do Item')
                                    ->disabled()->dehydrated(),
                                Forms\Components\Textarea::make('dec')->required()->label('Descrição Completa (DEC)')->columnSpanFull(),
                            ])
                            ->columns(2)
                            ->addActionLabel('Adicionar Item')
                            ->defaultItems(1)->reorderable(false)
                            ->live()
                            ->afterStateUpdated(fn (Get $get, Set $set) => self::updateTotals($get, $set))
                            ->deleteAction(fn (Forms\Components\Actions\Action $action) => $action->after(fn (Get $get, Set $set) => self::updateTotals($get, $set))),

                        Forms\Components\Placeholder::make('valor_total_geral')
                            ->label('Valor Total da Solicitação')
                            ->content(function (Get $get): string {
                                $total = collect($get('items'))->reduce(fn ($total, $item) => $total + (($item['quantidade'] ?? 0) * ($item['valor_unitario'] ?? 0)), 0);
                                return Number::currency($total, 'BRL');
                            }),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')->label('Objeto')->searchable(),
                Tables\Columns\TextColumn::make('coordination.name')->label('Coordenação')->sortable(),
                Tables\Columns\TextColumn::make('user.name')
                ->label('Solicitante')
                ->searchable()
                ->sortable(),
                Tables\Columns\TextColumn::make('coordination.name')->label('Coordenação'),
                Tables\Columns\TextColumn::make('data')->date('d/m/Y')->sortable(),
                Tables\Columns\TextColumn::make('status')->badge(),
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'pendente' => 'warning', 'aprovada' => 'info', 'efetivada' => 'success', default => 'gray',
                    }),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make()->label('Ver'),
                Tables\Actions\EditAction::make()->visible(fn (Compra $record) => $record->status !== 'efetivada'),
                Tables\Actions\DeleteAction::make()->visible(fn (Compra $record) => $record->status !== 'efetivada'),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    
    public static function updateTotals(Get $get, Set $set): void
    {
        collect($get('items'))->each(function ($item, $key) use ($set) {
            $subtotal = ($item['quantidade'] ?? 0) * ($item['valor_unitario'] ?? 0);
            $set("items.{$key}.valor_total", number_format($subtotal, 2, '.', ''));
        });
    }

     public static function mutateFormDataBeforeCreate(array $data): array
    {
        $data['user_id'] = Auth::id();
    
        return $data;
    }

    
    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCompras::route('/'),
            'create' => Pages\CreateCompra::route('/create'),
            'view' => Pages\ViewCompra::route('/{record:uuid}'),
            'edit' => Pages\EditCompra::route('/{record:uuid}/edit'),
        ];
    }
}