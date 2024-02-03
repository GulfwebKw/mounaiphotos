<?php

namespace App\Filament\Resources\Setting;

use App\Filament\Resources\Setting;
use App\Models\Holiday;
use Filament\Forms\Form;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Carbon;

class HolidayResource extends Resource
{
    protected static ?string $model = Holiday::class;
    protected static ?string $navigationGroup = 'Setting';

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('title')
                    ->maxLength(255)
                    ->required(),
                Forms\Components\Select::make('type')
                    ->options(['once' => 'Once','yearly' => 'Yearly'])
                    ->required(),
                Forms\Components\Select::make('year')
                    ->label('Year (if type is `Once`)')
                    ->options([
                        now()->year => now()->year,
                        now()->year + 1 => now()->year + 1,
                    ])
                    ->nullable(),
                Forms\Components\Select::make('month')
                    ->options(self::months())
                    ->required(),
                Forms\Components\Select::make('day')
                    ->options(self::days())
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->searchable(),
                Tables\Columns\TextColumn::make('year')
                    ->searchable(),
                Tables\Columns\TextColumn::make('month')
                    ->getStateUsing(fn ($record): ?string => self::months()[$record->month] ?? null)
                    ->searchable(),
                Tables\Columns\TextColumn::make('day')
                    ->searchable(),
                Tables\Columns\TextColumn::make('type')
                    ->searchable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('month')
            ->defaultSort('day');
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
            'index' => Setting\HolidayResource\Pages\ListHolidays::route('/'),
            'create' => Setting\HolidayResource\Pages\CreateHoliday::route('/create'),
            'edit' => Setting\HolidayResource\Pages\EditHoliday::route('/{record}/edit'),
        ];
    }


    private static function months(): array
    {
        return [
            1=> 'January',
            2 => 'February',
            3 => 'March',
            4=> 'April',
            5 => 'May',
            6 => 'June',
            7 => 'July',
            8 => 'August',
            9 => 'September',
            10 => 'October',
            11 => 'November',
            12 => 'December'
        ];
    }


    private static function days(): array
    {
        return [
            1=> '1',
            2 => '2',
            3 => '3',
            4=> '4',
            5 => '5',
            6 => '6',
            7 => '7',
            8 => '8',
            9 => '9',
            10 => '10',
            11 => '11',
            12 => '12',
            13 => '13',
            14 => '14',
            15 => '15',
            16 => '16',
            17 => '17',
            18 => '18',
            19 => '19',
            20 => '20',
            21 => '21',
            22 => '22',
            23 => '23',
            24 => '24',
            25 => '25',
            26 => '26',
            27 => '27',
            28 => '28',
            29 => '29',
            30 => '30',
            31 => '31',
        ];
    }
}
