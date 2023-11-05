<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermissionsSeeder extends Seeder
{
    public function run()
    {
        $prefixes = config('permissions.prefixes');

        $permissions = collect();

        collect(config('permissions.models'))->each(function ($el) use ($prefixes, &$permissions) {
            $el = str($el)->explode('\\')->last();
            $el = str()->snake($el);

            foreach ($prefixes as $prefix) {
                $permissions[] = [
                    'name' => "$el:$prefix",
                    'guard_name' => 'web',
                ];
            }
        });

        $permissions->each(function($el){
            if(!Permission::where('name', $el['name'])->exists()){
                Permission::create($el);
            }
        });
    }
}
