<?php

namespace App\Filament\Resources;

use App\Filament\Resources\RoleResource\Pages;
use App\Filament\Resources\RoleResource\RelationManagers;
use App\Models\Role;
use App\Models\Shop;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Filesystem\Filesystem;

class RoleResource extends Resource
{
    protected static ?string $model = Role::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static array $exclude = [
        Shop::class
    ];

    public static function getNavigationGroup(): ?string
    {
        return __('Roles');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Information')
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->label('name')
                            ->required()
                            ->maxLength(255)
                    ]),

                Forms\Components\Section::make('Permissions')
                    ->schema(self::permissionsSchemaSection())


            ]);
    }

    public static function permissionsSchemaSection(): array
    {
        $permissons = collect([
            'view_any',
            'view',
            'create',
            'update',
            'restore',
            'delete',
            'force_delete',
        ]);
        $inputs = [];
        $models = array_diff(self::getModels(), self::$exclude);
        foreach ($models as $model) {
            $model = str($model)->explode('\\')->last();
            $model = str($model)->snake();

            $options = $permissons->flatMap(fn($prefix) => ["$model:$prefix" => str($prefix)->headline()]);

            $inputs[] = Forms\Components\CheckboxList::make(str($model)->snake())
                ->options($options->toArray())
                ->columns(4)
                ->bulkToggleable()
                ->statePath('permissions');

        }

        return $inputs;
    }

    public static function getModels()
    {
        $modelClasses = [];

        // Specify the directory where your models are located
        $modelsDirectory = app_path('Models'); // Change this path as needed

        $filesystem = app(Filesystem::class);
        $files = $filesystem->allFiles($modelsDirectory);

        foreach ($files as $file) {
            $namespace = 'App\\Models';
            $class = $namespace . '\\' . $file->getBasename('.php');

            if (class_exists($class) && is_subclass_of($class, 'Illuminate\Database\Eloquent\Model')) {
                $modelClasses[] = $class;
            }
        }

        return $modelClasses;
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
