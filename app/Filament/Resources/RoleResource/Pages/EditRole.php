<?php

namespace App\Filament\Resources\RoleResource\Pages;

use App\Filament\Resources\RoleResource;
use App\Models\Role;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Database\Eloquent\Model;
use Spatie\Permission\Models\Permission;

class EditRole extends EditRecord
{
    protected static string $resource = RoleResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected function mutateFormDataBeforeFill(array $data): array
    {
        $data['permissions'] = Role::where('name', $data['name'])->first()->permissions()->pluck('name');

        return $data;
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        $data['permissions'] = Permission::whereIn('name', $data['permissions'])->pluck('id');

        return $data;
    }

    protected function handleRecordUpdate(Model $record, array $data): Model
    {
        $record->syncPermissions($data['permissions']);

        unset($data['permissions']);

        $record->update($data);

        return $record;
    }
}
