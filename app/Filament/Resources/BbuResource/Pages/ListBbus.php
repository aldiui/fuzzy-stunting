<?php

namespace App\Filament\Resources\BbuResource\Pages;

use App\Filament\Resources\BbuResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListBbus extends ListRecords
{
    protected static string $resource = BbuResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
