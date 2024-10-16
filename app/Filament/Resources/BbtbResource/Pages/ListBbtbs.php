<?php

namespace App\Filament\Resources\BbtbResource\Pages;

use App\Filament\Resources\BbtbResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListBbtbs extends ListRecords
{
    protected static string $resource = BbtbResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
