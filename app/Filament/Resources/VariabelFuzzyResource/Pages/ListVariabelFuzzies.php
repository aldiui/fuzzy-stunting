<?php

namespace App\Filament\Resources\VariabelFuzzyResource\Pages;

use App\Filament\Resources\VariabelFuzzyResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListVariabelFuzzies extends ListRecords
{
    protected static string $resource = VariabelFuzzyResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
