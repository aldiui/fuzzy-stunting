<?php

namespace App\Filament\Resources;

use App\Filament\Resources\VariabelFuzzyResource\Pages;
use App\Models\VariabelFuzzy;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class VariabelFuzzyResource extends Resource
{
    protected static ?string $model = VariabelFuzzy::class;

    protected static ?string $navigationIcon = 'heroicon-o-variable';

    protected static ?string $navigationLabel = 'Variabel Fuzzy';

    protected static ?string $navigationGroup = 'Data Master';

    protected static ?string $slug = 'variabel-fuzzy';

    protected static ?int $navigationSort = 6;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Data Variabel Fuzzy')
                    ->schema([
                        Forms\Components\TextInput::make('nama')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\Select::make('kriteria')
                            ->options([
                                'BBU' => 'BBU',
                                'TBU' => 'TBU',
                                'BBTB' => 'BBTB',
                            ])
                            ->required(),
                        Forms\Components\TextInput::make('range_awal')
                            ->label('Range Awal')
                            ->required()
                            ->numeric(),
                        Forms\Components\TextInput::make('range_akhir')
                            ->label('Range Akhir')
                            ->required()
                            ->numeric(),
                        Forms\Components\TextInput::make('himpunan_fuzzy_awal')
                            ->label('Himpunan Fuzzy Awal')
                            ->required()
                            ->numeric(),
                        Forms\Components\TextInput::make('himpunan_fuzzy_akhir')
                            ->label('Himpunan Fuzzy Akhir')
                            ->required()
                            ->numeric(),
                        Forms\Components\Select::make('tipe')
                            ->options([
                                'Trapesium' => 'Trapesium',
                                'Segitiga' => 'Segitiga',
                            ])
                            ->live()
                            ->required(),
                        Forms\Components\TextInput::make('interval_1')
                            ->afterStateHydrated(function ($state, $set, $component) {
                                $record = $component->getRecord();
                                if (is_null($state) && $record) {
                                    $interval = $record->interval;
                                    $set('interval_1', $interval[0] ?? null);
                                }
                            })
                            ->required()
                            ->numeric(),
                        Forms\Components\TextInput::make('interval_2')
                            ->afterStateHydrated(function ($state, $set, $component) {
                                $record = $component->getRecord();
                                if (is_null($state) && $record) {
                                    $interval = $record->interval;
                                    $set('interval_2', $interval[1] ?? null);
                                }
                            })
                            ->required()
                            ->numeric(),
                        Forms\Components\TextInput::make('interval_3')
                            ->afterStateHydrated(function ($state, $set, $component) {
                                $record = $component->getRecord();
                                if (is_null($state) && $record) {
                                    $interval = $record->interval;
                                    $set('interval_3', $interval[2] ?? null);
                                }
                            })
                            ->required()
                            ->numeric(),
                        Forms\Components\TextInput::make('interval_4')
                            ->afterStateHydrated(function ($state, $set, $component) {
                                $record = $component->getRecord();
                                if (is_null($state) && $record) {
                                    $interval = $record->interval;
                                    $set('interval_4', $interval[3] ?? null);
                                }
                            })
                            ->required(fn($get) => $get('tipe') === 'Trapesium')
                            ->numeric()
                            ->visible(fn($get) => $get('tipe') === 'Trapesium'),
                    ])->columns(3),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('nama')
                    ->searchable(),
                Tables\Columns\TextColumn::make('kriteria'),
                Tables\Columns\TextColumn::make('range_awal')
                    ->label('Range Awal')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('range_akhir')
                    ->label('Range Akhir')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('himpunan_fuzzy_awal')
                    ->label('Himpunan Fuzzy Awal')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('himpunan_fuzzy_akhir')
                    ->label('Himpunan Fuzzy Akhir')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('tipe'),
            ])
            ->filters([
                Tables\Filters\TrashedFilter::make(),
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
            'index' => Pages\ListVariabelFuzzies::route('/'),
            'create' => Pages\CreateVariabelFuzzy::route('/create'),
            'edit' => Pages\EditVariabelFuzzy::route('/{record}/edit'),
        ];
    }
}