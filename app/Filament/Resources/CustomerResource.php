<?php

namespace App\Filament\Resources;

use App\Models\Customer;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Filament\Forms\Components\TextInput;
use App\Filament\Resources\CustomerResource\Pages;
use Filament\Tables\Columns\TextColumn;

class CustomerResource extends Resource
{
    protected static ?string $model = Customer::class;

    protected static ?string $navigationGroup = 'Sales';
    protected static ?int $navigationSort = 10;
    protected static ?string $navigationIcon = 'heroicon-o-user';

    public static function form(Form $form): Form
    {
        return $form->schema([
            TextInput::make('name')
                ->required()
                ->maxLength(255),

            TextInput::make('email')
                ->email()
                ->nullable()
                ->maxLength(255),

            TextInput::make('phone')
                ->required()
                ->tel()
                ->unique(ignoreRecord: true)
                ->maxLength(20),

            TextInput::make('address')
                ->nullable()
                ->maxLength(500),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table->columns([
            TextColumn::make('name')->searchable()->sortable(),
            TextColumn::make('email')->label('Email')->sortable(),
            TextColumn::make('phone')->sortable(),
            TextColumn::make('address')->limit(50),
            TextColumn::make('created_at')->label('Created')->dateTime('d M Y'),
        ])
        ->filters([])
        ->actions([
            Tables\Actions\EditAction::make(),
        ])
        ->bulkActions([
            Tables\Actions\DeleteBulkAction::make(),
        ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCustomers::route('/'),
            'create' => Pages\CreateCustomer::route('/create'),
            'edit' => Pages\EditCustomer::route('/{record}/edit'),
        ];
    }
}
