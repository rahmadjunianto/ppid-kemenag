<?php

namespace App\Filament\Resources\PublicInformation\Pages;

use App\Filament\Resources\PublicInformation\PublicInformationResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListPublicInformation extends ListRecords
{
    protected static string $resource = PublicInformationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
