<?php

namespace App\Filament\Resources\Uts220101079Resource\Pages;

use App\Filament\Resources\Uts220101079Resource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListUts220101079s extends ListRecords
{
    protected static string $resource = Uts220101079Resource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
