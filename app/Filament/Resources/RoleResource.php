<?php

namespace App\Filament\Resources;

use App\Filament\Resources\RoleResource\Pages;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Tables\Actions;
use Filament\Tables\Actions\BulkActionGroup;
use Filament\Tables\Actions\DeleteBulkAction;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleResource extends Resource
{
    protected static ?string $model = Role::class;
    protected static ?string $navigationIcon = 'heroicon-o-key';
    protected static ?string $navigationGroup = '系統管理';

    protected static ?string $navigationLabel = '角色管理';
    protected static ?string $pluralNavigationLabel = '角色管理';
    protected static ?string $modelLabel = '角色';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name')
                    ->label('角色名稱')
                    ->required()
                    ->unique(ignoreRecord: true),
                Select::make('permissions')
                    ->label('權限')
                    ->multiple()
                    ->relationship('permissions', 'name')
                    ->options(Permission::all()->pluck('name', 'id'))
                    ->preload()
                    ->searchable(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('角色名稱')
                    ->searchable()
                    ->color(fn(string $state): string => match ($state) {
                        'admin' => 'danger',
                        default => 'success',
                    }),
                TextColumn::make('users.name')
                    ->label('使用者')
                    ->badge()
                    ->separator(','),
                TextColumn::make('permissions.name')
                    ->label('權限')
                    ->badge()
                    ->separator(',')
                    ->wrap(),
                TextColumn::make('created_at')
                    ->label('創建時間')
                    ->dateTime(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Actions\EditAction::make()
                    ->hidden(
                        fn($record) =>
                        $record->name === 'super-admin' &&
                            !auth()->user()->isSuperAdmin()
                    ),
                Actions\DeleteAction::make()
                    ->hidden(
                        fn($record) =>
                        in_array($record->name, ['super-admin', 'admin']) ||
                            !auth()->user()->isSuperAdmin()
                    ),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make()
                        ->hidden(
                            fn($records) =>
                            $records &&
                                ($records->contains('name', 'super-admin') ||
                                    $records->contains('name', 'admin')) ||
                                !auth()->user()->isSuperAdmin()
                        ),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListRoles::route('/'),
            'create' => Pages\CreateRole::route('/create'),
            'edit' => Pages\EditRole::route('/{record}/edit'),
        ];
    }
}
