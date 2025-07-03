<?php

namespace App\Filament\Resources;

use App\Filament\Resources\Uts220101079Resource\Pages;
use App\Filament\Resources\Uts220101079Resource\RelationManagers;
use App\Models\Uts220101079;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class Uts220101079Resource extends Resource
{
    protected static ?string $model = Uts220101079::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('judul_info')
                    ->required()
                    ->maxLength(50),
                Forms\Components\TextInput::make('konten_info')
                    ->required()
                    ->maxLength(100),
            
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('kode_info')
                    ->searchable(),
                Tables\Columns\TextColumn::make('judul_info')
                    ->searchable(),
                Tables\Columns\TextColumn::make('konten_info')
                    ->searchable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
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
            'index' => Pages\ListUts220101079s::route('/'),
            'create' => Pages\CreateUts220101079::route('/create'),
            'edit' => Pages\EditUts220101079::route('/{record}/edit'),
        ];
    }
}
