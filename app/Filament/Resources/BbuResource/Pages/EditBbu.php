<?php

namespace App\Filament\Resources\BbuResource\Pages;

use App\Filament\Resources\BbuResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditBbu extends EditRecord
{
    protected static string $resource = BbuResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
