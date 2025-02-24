<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PromotionResource\Pages;
use App\Models\Promotion;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Table;

class PromotionResource extends Resource
{
    protected static ?string $model = Promotion::class;
    protected static ?string $navigationIcon = 'heroicon-o-gift';
    protected static ?string $navigationGroup = '促銷管理';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name')
                    ->label('活動名稱')
                    ->required(),
                Select::make('type')
                    ->label('類型')
                    ->options([
                        'product' => '商品',
                        'treatment' => '療程',
                    ])
                    ->required(),
                Select::make('discount_type')
                    ->label('折扣類型')
                    ->options([
                        'percentage' => '百分比',
                        'fixed' => '固定金額',
                    ])
                    ->required(),
                TextInput::make('discount_value')
                    ->label('折扣值')
                    ->numeric()
                    ->required(),
                DateTimePicker::make('start_date')
                    ->label('開始時間')
                    ->required(),
                DateTimePicker::make('end_date')
                    ->label('結束時間')
                    ->required(),
                Toggle::make('member_only')
                    ->label('僅限會員'),
                Select::make('status')
                    ->label('狀態')
                    ->options([
                        'active' => '啟用',
                        'inactive' => '停用',
                    ])
                    ->default('active'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('活動名稱')
                    ->searchable(),
                TextColumn::make('type')
                    ->label('類型'),
                TextColumn::make('discount_type')
                    ->label('折扣類型'),
                TextColumn::make('discount_value')
                    ->label('折扣值'),
                TextColumn::make('start_date')
                    ->label('開始時間')
                    ->dateTime(),
                TextColumn::make('end_date')
                    ->label('結束時間')
                    ->dateTime(),
                ToggleColumn::make('member_only')
                    ->label('僅限會員'),
                TextColumn::make('status')
                    ->label('狀態')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'active' => 'success',
                        'inactive' => 'danger',
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
            'index' => Pages\ListPromotions::route('/'),
            'create' => Pages\CreatePromotion::route('/create'),
            'edit' => Pages\EditPromotion::route('/{record}/edit'),
        ];
    }
} 