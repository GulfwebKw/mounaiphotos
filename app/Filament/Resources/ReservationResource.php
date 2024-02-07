<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ReservationResource\Pages;
use App\Filament\Resources\ReservationResource\RelationManagers;
use App\Models\Reservation;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Carbon;

class ReservationResource extends Resource
{
    protected static ?string $model = Reservation::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Group::make()
                    ->schema([
                        Forms\Components\Section::make('Information')
                            ->schema([
                                Forms\Components\TextInput::make('name'),
                                Forms\Components\TextInput::make('phone'),
                                Forms\Components\DateTimePicker::make('from'),
                                Forms\Components\DateTimePicker::make('to'),
                                Forms\Components\Textarea::make('message'),
                                Forms\Components\Select::make('package_id')
                                    ->relationship('package', 'title')
                                    ->searchable()
                                    ->required(),
                            ])
                            ->columns(2),

                        Forms\Components\Section::make('Payment Information')
                            ->schema([
                                Forms\Components\TextInput::make('price'),
                                Forms\Components\TextInput::make('reference_number'),
                                Forms\Components\TextInput::make('invoice_id'),
                            ])
                            ->columns(2),
                    ])
                    ->columnSpan(['lg' => fn (?Reservation $record) => $record === null ? 3 : 2]),

                Forms\Components\Section::make()
                    ->schema([
                        Forms\Components\Placeholder::make('uuid'),

                        Forms\Components\Placeholder::make('created_at')
                            ->label('Created at')
                            ->content(fn (Reservation $record): ?string => $record->created_at?->diffForHumans()),

                        Forms\Components\Placeholder::make('paid_at')
                            ->label('Invoice paid at')
                            ->content(fn (Reservation $record): ?string => $record->updated_at?->diffForHumans()),

                        Forms\Components\Placeholder::make('updated_at')
                            ->label('Last modified at')
                            ->content(fn (Reservation $record): ?string => $record->updated_at?->diffForHumans()),

                    ])
                    ->columnSpan(1),
            ])->columns(3);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('uuid')
                    ->searchable()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('name')
                    ->searchable()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('phone')
                    ->searchable()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('from')
                    ->searchable()
                    ->dateTime()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('to')
                    ->searchable()
                    ->dateTime()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('package.title')
                    ->label('Package')
                    ->toggleable(),
                Tables\Columns\BooleanColumn::make('is_paid')
                    ->toggleable(),
            ])
            ->filters([
                Tables\Filters\TrashedFilter::make(),

                Tables\Filters\Filter::make('created_at')
                    ->form([
                        Forms\Components\DatePicker::make('created_from')
                            ->placeholder(fn ($state): string => 'Dec 18, ' . now()->subYear()->format('Y')),
                        Forms\Components\DatePicker::make('created_until')
                            ->placeholder(fn ($state): string => now()->format('M d, Y')),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['created_from'] ?? null,
                                fn (Builder $query, $date): Builder => $query->whereDate('created_at', '>=', $date),
                            )
                            ->when(
                                $data['created_until'] ?? null,
                                fn (Builder $query, $date): Builder => $query->whereDate('created_at', '<=', $date),
                            );
                    })
                    ->indicateUsing(function (array $data): array {
                        $indicators = [];
                        if ($data['created_from'] ?? null) {
                            $indicators['created_from'] = 'Application from ' . Carbon::parse($data['created_from'])->toFormattedDateString();
                        }
                        if ($data['created_until'] ?? null) {
                            $indicators['created_until'] = 'Application until ' . Carbon::parse($data['created_until'])->toFormattedDateString();
                        }

                        return $indicators;
                    }),

                Tables\Filters\Filter::make('from')
                    ->form([
                        Forms\Components\DatePicker::make('from_from')
                            ->placeholder(fn ($state): string => 'Dec 18, ' . now()->subYear()->format('Y')),
                        Forms\Components\DatePicker::make('from_until')
                            ->placeholder(fn ($state): string => now()->format('M d, Y')),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['from_from'] ?? null,
                                fn (Builder $query, $date): Builder => $query->whereDate('from', '>=', $date),
                            )
                            ->when(
                                $data['from_until'] ?? null,
                                fn (Builder $query, $date): Builder => $query->whereDate('from', '<=', $date),
                            );
                    })
                    ->indicateUsing(function (array $data): array {
                        $indicators = [];
                        if ($data['from_from'] ?? null) {
                            $indicators['from_from'] = 'Application from ' . Carbon::parse($data['from_from'])->toFormattedDateString();
                        }
                        if ($data['from_until'] ?? null) {
                            $indicators['from_until'] = 'Application until ' . Carbon::parse($data['from_until'])->toFormattedDateString();
                        }

                        return $indicators;
                    }),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
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
            'index' => Pages\ListReservations::route('/'),
            'create' => Pages\CreateReservation::route('/create'),
            'edit' => Pages\EditReservation::route('/{record}/edit'),
        ];
    }
    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->withoutGlobalScope(SoftDeletingScope::class);
    }
    public static function canCreate(): bool
    {
        return false;
    }
}
