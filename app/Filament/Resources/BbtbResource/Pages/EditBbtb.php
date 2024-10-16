<?php

namespace App\Filament\Resources\BbtbResource\Pages;

use App\Filament\Resources\BbtbResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditBbtb extends EditRecord
{
    protected static string $resource = BbtbResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
