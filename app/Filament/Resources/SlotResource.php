<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SlotResource\Pages;
use App\Filament\Resources\SlotResource\RelationManagers;
use App\Models\Slot;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class SlotResource extends Resource
{
    protected static ?string $model = Slot::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('day')
                    ->options(self::weeks())
                    ->required(),
                Forms\Components\TimePicker::make('from')
                    ->required(),
                Forms\Components\TimePicker::make('to')
                    ->required(),
                Forms\Components\TextInput::make('slot')
                    ->numeric()
                    ->label('Slot time in minute')
                    ->minValue(0)
                    ->required(),
                Forms\Components\TextInput::make('rest')
                    ->numeric()
                    ->label('Rest time in minute')
                    ->minValue(0)
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('day')
                    ->getStateUsing(fn ($record): ?string => self::weeks()[$record->day] ?? null)
                    ->searchable(),
                Tables\Columns\TextColumn::make('from')
                    ->time(),
                Tables\Columns\TextColumn::make('to')
                    ->time(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
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
            'index' => Pages\ListSlots::route('/'),
            'create' => Pages\CreateSlot::route('/create'),
            'edit' => Pages\EditSlot::route('/{record}/edit'),
        ];
    }

    private static function weeks()
    {
        return [
            0 => 'Sunday' ,1 => 'Monday',2 => 'Tuesday',3 => 'Wednesday',4 => 'Thursday',5 => 'Friday' ,6 => 'Saturday'
        ];
    }
}
