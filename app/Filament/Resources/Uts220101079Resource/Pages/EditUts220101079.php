<?php

namespace App\Filament\Resources\Uts220101079Resource\Pages;

use App\Filament\Resources\Uts220101079Resource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditUts220101079 extends EditRecord
{
    protected static string $resource = Uts220101079Resource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
