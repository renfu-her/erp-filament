<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProductResource\Pages;
use App\Models\Product;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class ProductResource extends Resource
{
    protected static ?string $model = Product::class;
    protected static ?string $navigationIcon = 'heroicon-o-shopping-bag';
    protected static ?string $navigationGroup = '產品管理';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name')
                    ->label('名稱')
                    ->required(),
                Select::make('type')
                    ->label('類型')
                    ->options([
                        'spa' => 'SPA',
                        'product' => '商品',
                        'treatment' => '療程',
                        'experience' => '體驗',
                    ])
                    ->required(),
                Textarea::make('description')
                    ->label('描述'),
                TextInput::make('price')
                    ->label('價格')
                    ->numeric()
                    ->required(),
                TextInput::make('stock')
                    ->label('庫存')
                    ->numeric()
                    ->default(0),
                TextInput::make('safety_stock')
                    ->label('安全庫存')
                    ->numeric()
                    ->default(0),
                TextInput::make('discount')
                    ->label('折扣')
                    ->numeric()
                    ->default(0),
                Select::make('status')
                    ->label('狀態')
                    ->options([
                        'active' => '上架',
                        'inactive' => '下架',
                    ])
                    ->default('active'),
                FileUpload::make('image')
                    ->label('圖片')
                    ->image()
                    ->directory('products'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('image')
                    ->label('圖片'),
                TextColumn::make('name')
                    ->label('名稱')
                    ->searchable(),
                TextColumn::make('type')
                    ->label('類型'),
                TextColumn::make('price')
                    ->label('價格')
                    ->money('TWD'),
                TextColumn::make('stock')
                    ->label('庫存'),
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
            'index' => Pages\ListProducts::route('/'),
            'create' => Pages\CreateProduct::route('/create'),
            'edit' => Pages\EditProduct::route('/{record}/edit'),
        ];
    }
} 