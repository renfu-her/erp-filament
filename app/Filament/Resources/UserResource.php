<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Models\User;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Tables\Actions;
use Filament\Tables\Actions\BulkActionGroup;
use Filament\Tables\Actions\DeleteBulkAction;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;

class UserResource extends Resource
{
    protected static ?string $model = User::class;
    protected static ?string $navigationIcon = 'heroicon-o-user-group';
    protected static ?string $navigationGroup = '系統管理';

    protected static ?string $navigationLabel = '員工管理';
    protected static ?string $pluralNavigationLabel = '員工管理';
    protected static ?string $modelLabel = '員工';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name')
                    ->label('姓名')
                    ->required(),
                TextInput::make('email')
                    ->label('電子郵件')
                    ->email()
                    ->required()
                    ->unique(ignoreRecord: true),
                TextInput::make('password')
                    ->label('密碼')
                    ->password()
                    ->dehydrateStateUsing(fn($state) => Hash::make($state))
                    ->required(fn(string $context): bool => $context === 'create')
                    ->minLength(8)
                    ->same('passwordConfirmation'),
                TextInput::make('passwordConfirmation')
                    ->label('確認密碼')
                    ->password()
                    ->required(fn(string $context): bool => $context === 'create')
                    ->minLength(8),
                Select::make('roles')
                    ->label('角色')
                    ->multiple()
                    ->relationship('roles', 'name')
                    ->preload(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('姓名')
                    ->searchable(),
                TextColumn::make('email')
                    ->label('電子郵件')
                    ->searchable(),
                TextColumn::make('roles.name')
                    ->label('角色')
                    ->badge()
                    ->separator(',')
                    ->color(fn(string $state): string => match ($state) {
                        'admin' => 'danger',
                        default => 'success',
                    }),
                TextColumn::make('created_at')
                    ->label('創建時間')
                    ->dateTime(format: 'Y-m-d H:i'),
            ])
            ->filters([
                //
            ])
            ->actions([
                Actions\EditAction::make(),
                Actions\DeleteAction::make()
                    ->hidden(fn($record) => $record->email === 'admin@admin.com'),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make()
                        ->hidden(fn($records) => $records && $records->contains('email', 'admin@admin.com')),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }
}
