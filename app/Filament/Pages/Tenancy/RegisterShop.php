<?php

namespace App\Filament\Pages\Tenancy;

use App\Models\Shop;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Pages\Tenancy\RegisterTenant;

class RegisterShop extends RegisterTenant
{
    public static function getLabel(): string
    {
        return 'Register Shop';
    }

    public function form(Form $form): Form
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

    protected function handleRegistration(array $data): Shop
    {
        $shop = Shop::create([
            ...$data,
            'user_id' => auth()->user()->id,
        ]);

        $shop->members()->attach(auth()->user());

        return $shop;
    }
}
