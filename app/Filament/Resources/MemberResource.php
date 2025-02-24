<?php

namespace App\Filament\Resources;

use App\Filament\Resources\MemberResource\Pages;
use App\Models\Member;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Tables\Actions;
use Filament\Tables\Actions\BulkActionGroup;
use Filament\Tables\Actions\DeleteBulkAction;

class MemberResource extends Resource
{
    protected static ?string $model = Member::class;
    protected static ?string $navigationIcon = 'heroicon-o-users';
    protected static ?string $navigationGroup = '客戶管理';

    protected static ?string $navigationLabel = '會員管理';

    protected static ?string $pluralNavigationLabel = '會員管理';
    
    protected static ?string $modelLabel = '會員';

    

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name')
                    ->label('姓名')
                    ->required(),
                TextInput::make('phone')
                    ->label('電話')
                    ->tel(),
                TextInput::make('email')
                    ->label('電子郵件')
                    ->email(),
                Select::make('level')
                    ->label('會員等級')
                    ->options([
                        'normal' => '一般會員',
                        'vip' => 'VIP會員',
                    ])
                    ->default('normal'),
                TextInput::make('total_purchase')
                    ->label('消費總額')
                    ->numeric()
                    ->disabled(),
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
                    ->label('姓名')
                    ->searchable(),
                TextColumn::make('phone')
                    ->label('電話'),
                TextColumn::make('email')
                    ->label('電子郵件'),
                TextColumn::make('level')
                    ->label('會員等級'),
                TextColumn::make('total_purchase')
                    ->label('消費總額')
                    ->money('TWD'),
                TextColumn::make('status')
                    ->label('狀態')
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'active' => 'success',
                        'inactive' => 'danger',
                    }),
            ])
            ->filters([
                //
            ])
            ->actions([
                Actions\EditAction::make(),
                Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListMembers::route('/'),
            'create' => Pages\CreateMember::route('/create'),
            'edit' => Pages\EditMember::route('/{record}/edit'),
        ];
    }
}
