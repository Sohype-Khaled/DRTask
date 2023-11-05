<?php

namespace App\Filament\Resources\RoleResource\Pages;

use App\Filament\Resources\RoleResource;
use Filament\Facades\Filament;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;
use Spatie\Permission\Models\Permission;

class CreateRole extends CreateRecord
{
    protected static string $resource = RoleResource::class;

    protected function mutateFormDataBeforeSave(array $data): array
    {
        $data['permissions'] = Permission::whereIn('name', $data['permissions'])->pluck('id');

        return $data;
    }

    protected function handleRecordCreation(array $data): Model
    {
        $permissions = $data['permissions'];
        $data['shop_id'] = Filament::getTenant()->id;
        unset($data['permissions']);

        $record = static::getModel()::create($data);

        $record->syncPermissions($permissions);

        return $record;
    }
}
