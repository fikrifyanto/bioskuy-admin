<?php

namespace App\Filament\Resources\TheaterResource\RelationManagers;

use App\Models\Schedule;
use App\Models\Theater;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Support\RawJs;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class SchedulesRelationManager extends RelationManager
{
    protected static string $relationship = 'schedules';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('movie_id')
                    ->relationship('movie', 'title'),
                Forms\Components\DateTimePicker::make('start_time')
                    ->native(false),
                Forms\Components\DateTimePicker::make('end_time')
                    ->native(false),
                Forms\Components\TextInput::make('price')
                    ->prefix('Rp')
                    ->mask(RawJs::make('$money($input)'))
                    ->dehydrateStateUsing(function ($state) {
                        return (int) preg_replace('/[^\d]/', '', $state);
                    })
                ,
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('movie.title')
            ->columns([
                Tables\Columns\TextColumn::make('movie_id')
                    ->name('movie.title'),
                Tables\Columns\TextColumn::make('start_time')
                    ->dateTime(),
                Tables\Columns\TextColumn::make('end_time')
                    ->dateTime(),
                Tables\Columns\TextColumn::make('price')
                    ->prefix('Rp ')
                    ->formatStateUsing(fn ($state) => number_format($state, 0, '.', ','))
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()
                    ->after(function (Schedule $record) {
                        $theater = Theater::find($record->theater_id);

                        // Buat array huruf A sampai P, kecualikan I dan O
                        $rows = array_filter(range('A', 'P'), fn($r) => !in_array($r, ['I', 'O']));

                        $totalSeats = 0;
                        foreach ($rows as $row) {
                            for ($i = 1; $i <= 14; $i++) {
                                // Hentikan jika kapasitas sudah terpenuhi
                                if ($totalSeats >= $theater->capacity) break;

                                $record->seats()->create([
                                    'seat_number' => $row . $i,
                                ]);

                                $totalSeats++;
                            }
                        }
                    }),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
