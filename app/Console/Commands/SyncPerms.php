<?php

namespace App\Console\Commands;

use App\Models\Shop;
use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use Spatie\Permission\Models\Permission;

class SyncPerms extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:sync-perms {--C|clean}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sync permissions for each model';


    protected array $exclude = [
        Shop::class
    ];

    protected array $permissions = [
        'view_any',
        'view',
        'create',
        'update',
        'restore',
        'delete',
        'force_delete',
    ];

    /**
     * Execute the console command.
     */
    public function handle()
    {

        if ($this->option('clean')) {
            Permission::query()->delete();
        }

        $models = array_diff($this->getModels(), $this->exclude);

        $this->createPermissions($models);
    }

    public function getModels()
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


    public function createPermissions($models)
    {
        foreach ($models as $model) {
            $model = str($model)->explode('\\')->last();
            $model = str()->snake($model);

            foreach ($this->permissions as $permission) {
                $name = "$model:$permission";
                if (!Permission::where('name',)->where('guard_name', 'web')->exists()) {
                    Permission::create([
                        'name' => $name,
                        'guard_name' => 'web',
                    ]);
                }
            }
        }
    }
}
