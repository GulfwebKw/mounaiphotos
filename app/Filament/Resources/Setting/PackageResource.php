<?php

namespace App\Filament\Resources\Setting;

use App\Filament\Resources\Setting\PackageResource\Pages;
use App\Filament\Resources\Setting\PackageResource\RelationManagers;
use App\Filament\Resources\Setting;
use App\Models\Package;
use Filament\Forms;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Form;
use Filament\Forms\Components\Actions\Action;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\HtmlString;

class PackageResource extends Resource
{
    protected static ?string $model = Package::class;

    protected static ?string $navigationGroup = 'Setting';
    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function getGloballySearchableAttributes(): array
    {
        return ['title', 'description' , 'included'];
    }

    public static function getGlobalSearchResultTitle(Model $record): string | Htmlable
    {
        return new HtmlString('<div style="display: flex;justify-content: flex-start;align-items: center;column-gap: 10px;"><img src="'.asset('/storage/'.$record->picture ).'" style="max-width:40px;max-height:40px;border-radius: 100%;"><div>'. $record->title.'</div></div>');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                        Forms\Components\Section::make()
                            ->schema([
                                Forms\Components\TextInput::make('title')
                                    ->maxLength(255)
                                    ->required(),
                                Forms\Components\TextInput::make('price')
                                    ->step(0.01)
                                    ->minValue(0)
                                    ->type('number')
                                    ->required(),
                                Forms\Components\Textarea::make('description')
                                    ->rows(8)
                                    ->required(),
                                Forms\Components\Textarea::make('included')
                                    ->rows(8)
                                    ->label('Included in this package')
                                    ->required(),
                                Forms\Components\TextInput::make('number_of_persons')
                                    ->type('number')
                                    ->minValue(0)
                                    ->label('Max number of persons per session')
                                    ->required(),
                                Forms\Components\Toggle::make('is_active'),
                                Forms\Components\FileUpload::make('picture')
                                    ->image(),
                            ])->columns(2),
                        Forms\Components\Section::make('Package Options')
                            ->headerActions([
                                Action::make('reset')
                                    ->modalHeading('Are you sure?')
                                    ->modalDescription('All existing items will be removed from the Package.')
                                    ->requiresConfirmation()
                                    ->color('danger')
                                    ->action(fn (Forms\Set $set) => $set('items', [])),
                            ])
                            ->schema([
                                Repeater::make('options')
                                    ->relationship()
                                    ->schema([
                                        Forms\Components\TextInput::make('title')
                                            ->required(),
                                        Forms\Components\Toggle::make('is_active'),
                                        Forms\Components\FileUpload::make('picture')
                                            ->image(),
                                    ])
                                    ->orderColumn('ordering')
                                    ->defaultItems(1)
                                    ->hiddenLabel()
                                    ->columns(3)
                                    ->required()
                            ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('picture'),
                Tables\Columns\TextColumn::make('title')
                    ->searchable(),
                Tables\Columns\TextColumn::make('price'),
                Tables\Columns\BooleanColumn::make('is_active'),
                Tables\Columns\TextColumn::make('description')
                    ->hidden()
                    ->searchable(),
                Tables\Columns\TextColumn::make('included')
                    ->hidden()
                    ->searchable(),
            ])
            ->filters([
                Tables\Filters\TrashedFilter::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('ordering')
            ->reorderable('ordering');
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
            'index' => Setting\PackageResource\Pages\ListPackages::route('/'),
            'create' => Setting\PackageResource\Pages\CreatePackage::route('/create'),
            'edit' => Setting\PackageResource\Pages\EditPackage::route('/{record}/edit'),
        ];
    }
}
