<?php

namespace App\Filament\Resources\TbuResource\Pages;

use App\Filament\Resources\TbuResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditTbu extends EditRecord
{
    protected static string $resource = TbuResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
