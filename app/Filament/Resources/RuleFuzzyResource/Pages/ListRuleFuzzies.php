<?php

namespace App\Filament\Resources\RuleFuzzyResource\Pages;

use App\Filament\Resources\RuleFuzzyResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListRuleFuzzies extends ListRecords
{
    protected static string $resource = RuleFuzzyResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
