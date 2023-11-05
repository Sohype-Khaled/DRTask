<?php

namespace App\Filament\Resources;

use App\Filament\Resources\RoleResource\Pages;
use App\Filament\Resources\RoleResource\RelationManagers;
use App\Models\Role;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class RoleResource extends Resource
{
    protected static ?string $model = Role::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function getNavigationGroup(): ?string
    {
        return __('Roles');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->label('name')
                    ->required()
                    ->maxLength(255),
                Forms\Components\Grid::make()
                    ->schema(static::getResourceEntitiesSchema())
                    ->columns(12),

            ]);
    }

    public static function getResourceEntitiesSchema(): array
    {
        $prefixes = collect(config('permissions.prefixes'));
        $inputs = [];

        foreach (config('permissions.models') as $model) {
            $model = str($model)->explode('\\')->last();
            $model = str($model)->snake();

            $options = $prefixes->flatMap(fn($prefix) => ["$model:$prefix" => str($prefix)->headline()]);

            $inputs[] = Forms\Components\Section::make()
                ->schema([
                    Forms\Components\CheckboxList::make(str($model)->snake())
                        ->options($options->toArray())
                        ->columns(2)
                        ->searchable()
                        ->bulkToggleable()
                        ->statePath('permissions')
                ])->columnSpan([
                    'sm' => 12,
                    'lg' => 6,
                    'xl' => 4,
                ]);
        }

        return $inputs;
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name'),
                Tables\Columns\TextColumn::make('guard_name')
                    ->label('Guard')
                    ->badge(),
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
            'index' => Pages\ListRoles::route('/'),
            'create' => Pages\CreateRole::route('/create'),
            'edit' => Pages\EditRole::route('/{record}/edit'),
        ];
    }
}
