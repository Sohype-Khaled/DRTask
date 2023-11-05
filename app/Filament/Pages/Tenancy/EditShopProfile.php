<?php

namespace App\Filament\Pages\Tenancy;

use Filament\Forms;
use Filament\Pages\Tenancy\EditTenantProfile;

class EditShopProfile extends EditTenantProfile
{
    public static function getLabel(): string
    {
        return 'Shop profile';
    }

    public function form(Forms\Form $form): Forms\Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->unique()
                    ->maxLength(255)
                    ->required(),
                Forms\Components\TextInput::make('slug')
                    ->unique()
                    ->maxLength(255)
                    ->required(),
            ]);
    }
}
