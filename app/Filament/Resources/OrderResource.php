<?php

namespace App\Filament\Resources;

use App\Filament\Resources\OrderResource\Pages;
use App\Models\Order;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class OrderResource extends Resource
{
    protected static ?string $model = Order::class;
    protected static ?string $navigationIcon = 'heroicon-o-shopping-cart';
    protected static ?string $navigationGroup = '訂單管理';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('member_id')
                    ->label('會員')
                    ->relationship('member', 'name')
                    ->searchable()
                    ->required(),
                TextInput::make('order_number')
                    ->label('訂單編號')
                    ->required()
                    ->unique(ignoreRecord: true),
                TextInput::make('total_amount')
                    ->label('總金額')
                    ->numeric()
                    ->required(),
                Select::make('payment_method')
                    ->label('付款方式')
                    ->options([
                        'cash' => '現金',
                        'credit_card' => '信用卡',
                        'installment' => '分期',
                    ])
                    ->required(),
                Select::make('payment_status')
                    ->label('付款狀態')
                    ->options([
                        'pending' => '待付款',
                        'paid' => '已付款',
                        'refunded' => '已退款',
                    ])
                    ->default('pending'),
                Select::make('order_status')
                    ->label('訂單狀態')
                    ->options([
                        'pending' => '處理中',
                        'completed' => '已完成',
                        'cancelled' => '已取消',
                    ])
                    ->default('pending'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('order_number')
                    ->label('訂單編號')
                    ->searchable(),
                TextColumn::make('member.name')
                    ->label('會員'),
                TextColumn::make('total_amount')
                    ->label('總金額')
                    ->money('TWD'),
                TextColumn::make('payment_method')
                    ->label('付款方式'),
                TextColumn::make('payment_status')
                    ->label('付款狀態')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'pending' => 'warning',
                        'paid' => 'success',
                        'refunded' => 'danger',
                    }),
                TextColumn::make('order_status')
                    ->label('訂單狀態')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'pending' => 'warning',
                        'completed' => 'success',
                        'cancelled' => 'danger',
                    }),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListOrders::route('/'),
            'create' => Pages\CreateOrder::route('/create'),
            'edit' => Pages\EditOrder::route('/{record}/edit'),
        ];
    }
} 