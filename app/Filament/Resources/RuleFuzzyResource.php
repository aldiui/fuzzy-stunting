<?php

namespace App\Filament\Resources;

use App\Filament\Resources\RuleFuzzyResource\Pages;
use App\Models\IndexFuzzy;
use App\Models\RuleFuzzy;
use App\Models\VariabelFuzzy;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class RuleFuzzyResource extends Resource
{
    protected static ?string $model = RuleFuzzy::class;

    protected static ?string $navigationIcon = 'heroicon-o-arrow-turn-left-up';

    protected static ?string $navigationLabel = 'Rule Fuzzy';

    protected static ?string $navigationGroup = 'Data Master';

    protected static ?string $slug = 'rule-fuzzy';

    protected static ?int $navigationSort = 8;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('variabel_fuzzy_bbu_id')
                    ->label('Variabel Fuzzy BB/U')
                    ->required()
                    ->options(VariabelFuzzy::where('kriteria', 'BBU')->get()->pluck('nama', 'id')),
                Forms\Components\Select::make('variabel_fuzzy_tbu_id')
                    ->label('Variabel Fuzzy TB/U')
                    ->required()
                    ->options(VariabelFuzzy::where('kriteria', 'TBU')->get()->pluck('nama', 'id')),
                Forms\Components\Select::make('variabel_fuzzy_bbtb_id')
                    ->label('Variabel Fuzzy BB/TB')
                    ->required()
                    ->options(VariabelFuzzy::where('kriteria', 'BBTB')->get()->pluck('nama', 'id')),
                Forms\Components\Select::make('index_fuzzy_id')
                    ->label('Kondisi Anak')
                    ->required()
                    ->options(IndexFuzzy::all()->pluck('nama', 'id')),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('variabelFuzzyBbu.nama')
                    ->label('BB/U')
                    ->numeric()
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('variabelFuzzyTbu.nama')
                    ->label('TB/U')
                    ->numeric()
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('variabelFuzzyBbtb.nama')
                    ->label('BB/TB')
                    ->numeric()
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('indexFuzzy.nama')
                    ->label('Kondisi Anak')
                    ->searchable(),
            ])
            ->filters([
                Tables\Filters\TrashedFilter::make(),
                Tables\Filters\SelectFilter::make('variabel_fuzzy_bbu_id')
                    ->label('Variabel Fuzzy BB/U')
                    ->options(VariabelFuzzy::where('kriteria', 'BBU')->get()->pluck('nama', 'id')),
                Tables\Filters\SelectFilter::make('variabel_fuzzy_tbu_id')
                    ->label('Variabel Fuzzy TB/U')
                    ->options(VariabelFuzzy::where('kriteria', 'TBU')->get()->pluck('nama', 'id')),
                Tables\Filters\SelectFilter::make('variabel_fuzzy_bbtb_id')
                    ->label('Variabel Fuzzy BB/TB')
                    ->options(VariabelFuzzy::where('kriteria', 'BBTB')->get()->pluck('nama', 'id')),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
                Tables\Actions\RestoreAction::make(),
                Tables\Actions\ForceDeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\RestoreBulkAction::make(),
                    Tables\Actions\ForceDeleteBulkAction::make(),
                ]),
            ])
            ->paginated([25, 50, 100, 'all']);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListRuleFuzzies::route('/'),
        ];
    }
}
