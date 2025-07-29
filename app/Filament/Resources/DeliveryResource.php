<?php

namespace App\Filament\Resources;

use App\Filament\Resources\DeliveryResource\Pages;
use App\Models\Delivery;
use App\Models\Sale;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Table;
use Filament\Tables;

class DeliveryResource extends Resource
{
    protected static ?string $model = Delivery::class;

    protected static ?string $navigationIcon = 'heroicon-o-truck';
    protected static ?string $navigationGroup = 'Sales';
    protected static ?int $navigationSort = 22;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('sale_id')
                    ->label('Sale')
                    ->relationship('sale', 'invoice_number')
                    ->required(),

                Forms\Components\TextInput::make('tracking_number')
                    ->label('Tracking Number')
                    ->maxLength(255),

                Forms\Components\TextInput::make('courier')
                    ->label('Courier')
                    ->maxLength(255),

                Forms\Components\Select::make('status')
                    ->options([
                        'pending' => 'Pending',
                        'shipped' => 'Shipped',
                        'delivered' => 'Delivered',
                    ])
                    ->default('pending')
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('sale.invoice_number')->label('Invoice'),
                Tables\Columns\TextColumn::make('tracking_number'),
                Tables\Columns\TextColumn::make('courier'),
                Tables\Columns\BadgeColumn::make('status')->colors([
                    'secondary' => 'pending',
                    'warning' => 'shipped',
                    'success' => 'delivered',
                ]),
                Tables\Columns\TextColumn::make('created_at')->dateTime('d M Y'),
            ])
            ->filters([]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListDeliveries::route('/'),
            'create' => Pages\CreateDelivery::route('/create'),
            'edit' => Pages\EditDelivery::route('/{record}/edit'),
        ];
    }
}
