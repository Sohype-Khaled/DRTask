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
        return 'Submit';
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Fieldset::make('Select Shop')
                    ->schema([
                        Forms\Components\TextInput::make('shop-slug')
                            ->label('Select Shop')
                            ->maxLength(255)
                            ->requiredWithout('name,slug')
                    ]),

                Forms\Components\Fieldset::make('Create New Shop')
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->unique()
                            ->maxLength(255)
                            ->requiredWith('slug')
                            ->requiredWithout('shop-slug'),
                        Forms\Components\TextInput::make('slug')
                            ->unique()
                            ->maxLength(255)
                            ->requiredWith('name')
                            ->requiredWithout('shop-slug'),
                    ])
            ]);
    }

    protected function handleRegistration(array $data): Shop
    {
        if ($data['shop-slug'] && Shop::where('slug', $data['shop-slug'])->exists()) {
            $shop = Shop::where('slug', $data['shop-slug'])->firstOrFail();
        } else {
            $shop = Shop::create([
                'name' => $data['name'],
                'slug' => $data['slug'],
                'user_id' => auth()->user()->id,
            ]);

        }

        $shop->members()->attach(auth()->user());

        return $shop;
    }


}
