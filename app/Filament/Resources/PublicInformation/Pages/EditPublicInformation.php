<?php

namespace App\Filament\Resources\PublicInformation\Pages;

use App\Filament\Resources\PublicInformation\PublicInformationResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditPublicInformation extends EditRecord
{
    protected static string $resource = PublicInformationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
