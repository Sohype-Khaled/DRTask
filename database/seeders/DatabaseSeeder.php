<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Role;
use App\Models\Shop;
use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {

        $user = User::create([
            "name" => "Admin",
            "email" => "test@aol.com",
            "password" => bcrypt("123"),
        ]);

        $shop = Shop::create([
            "name" => "Main Shop",
            "user_id" => $user->id,
            "slug" => "main-shop"
        ]);

        $shop->members()->attach($user->id);

        $role = Role::create([
            "name" => "super-admin",
            "guard_name" => "web",
            "shop_id" => $shop->id,
        ]);

        $user->assignRole($role);

        $this->call([
            PermissionsSeeder::class,
        ]);

        $role->syncPermissions(Permission::pluck('id'));
    }
}
