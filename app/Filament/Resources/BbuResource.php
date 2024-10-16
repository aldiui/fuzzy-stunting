<?php

namespace App\Filament\Resources;

use App\Filament\Resources\BbuResource\Pages;
use App\Models\Bbu;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class BbuResource extends Resource
{
    protected static ?string $model = Bbu::class;

    protected static ?string $navigationIcon = 'heroicon-o-scale';

    protected static ?string $navigationLabel = 'Z-Score BB/U';

    protected static ?string $navigationGroup = 'Data Master';

    protected static ?string $slug = 'z-score-bbu';

    protected static ?int $navigationSort = 3;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Data Z-Score BB/U')
                    ->schema([
                        Forms\Components\TextInput::make('umur')
                            ->required()
                            ->numeric(),
                        Forms\Components\TextInput::make('standar_deviasi_minus_3')
                            ->label('-3 SD')
                            ->required()
                            ->numeric(),
                        Forms\Components\TextInput::make('standar_deviasi_minus_2')
                            ->label('-2 SD')
                            ->required()
                            ->numeric(),
                        Forms\Components\TextInput::make('standar_deviasi_minus_1')
                            ->label('-1 SD')
                            ->required()
                            ->numeric(),
                        Forms\Components\TextInput::make('standar_deviasi_median')
                            ->label('Median')
                            ->required()
                            ->numeric(),
                        Forms\Components\TextInput::make('standar_deviasi_plus_1')
                            ->label('+1 SD')
                            ->required()
                            ->numeric(),
                        Forms\Components\TextInput::make('standar_deviasi_plus_2')
                            ->label('+2 SD')
                            ->required()
                            ->numeric(),
                        Forms\Components\TextInput::make('standar_deviasi_plus_3')
                            ->label('+3 SD')
                            ->required()
                            ->numeric(),
                        Forms\Components\Select::make('jenis_kelamin')
                            ->label('Jenis Kelamin')
                            ->options([
                                'Laki-Laki' => 'Laki-Laki',
                                'Perempuan' => 'Perempuan',
                            ])
                            ->required(),
                    ])->columns(3),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('umur')
                    ->searchable()
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('standar_deviasi_minus_3')
                    ->label('-3 SD')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('standar_deviasi_minus_2')
                    ->label('-2 SD')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('standar_deviasi_minus_1')
                    ->label('-1 SD')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('standar_deviasi_median')
                    ->label('Median')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('standar_deviasi_plus_1')
                    ->label('+1 SD')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('standar_deviasi_plus_2')
                    ->label('+2 SD')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('standar_deviasi_plus_3')
                    ->label('+3 SD')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('jenis_kelamin')
                    ->label('Jenis Kelamin'),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('jenis_kelamin')
                    ->options([
                        'Laki-Laki' => 'Laki-Laki',
                        'Perempuan' => 'Perempuan',
                    ]),
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
            'index' => Pages\ListBbus::route('/'),
            'create' => Pages\CreateBbu::route('/create'),
            'edit' => Pages\EditBbu::route('/{record}/edit'),
        ];
    }
}