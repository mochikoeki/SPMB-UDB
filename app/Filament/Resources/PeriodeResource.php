<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PeriodeResource\Pages;
use App\Filament\Resources\PeriodeResource\RelationManagers;
use App\Models\Periode;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PeriodeResource extends Resource
{
    protected static ?string $model = Periode::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('id')
                    ->required()
                    ->label("Tahun")
                    ->numeric()
                    ->minValue(2025),
                Forms\Components\Hidden::make('aktif')->default(0)
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->label('Tahun')
                    ->sortable(),

                Tables\Columns\TextColumn::make('aktif')
                    ->formatStateUsing(function ($record): string {
                        if ($record->aktif == 1) {
                            return "Ya";
                        } else {
                            return "Tidak";
                        }
                    }),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),

                Tables\Actions\Action::make('Aktifkan')
                    ->icon('heroicon-s-eye')
                    ->action(function (Periode $record, array $data): void {
                        //ubah aktif=1 pada record yang dipilih

                        $record->aktif = 1;
                        $record->save();

                        //ubah aktif=0 selain record dipilih
                        Periode::where('id', '<>', $record->id)->update(['aktif' => 0]);
                    })
                    //membutuhkan konfirmasi
                    ->requiresConfirmation()
                    //terlihat jika nilai aktif=0
                    ->visible(function (Periode $record) {
                        return $record->aktif == 0;
                    })
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
            'index' => Pages\ListPeriodes::route('/'),
            'create' => Pages\CreatePeriode::route('/create'),
            'edit' => Pages\EditPeriode::route('/{record}/edit'),
        ];
    }
}
