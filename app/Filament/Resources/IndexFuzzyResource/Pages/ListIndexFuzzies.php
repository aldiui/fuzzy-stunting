<?php

namespace App\Filament\Resources\IndexFuzzyResource\Pages;

use App\Filament\Resources\IndexFuzzyResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListIndexFuzzies extends ListRecords
{
    protected static string $resource = IndexFuzzyResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
