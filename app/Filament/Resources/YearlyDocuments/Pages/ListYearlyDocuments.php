<?php

namespace App\Filament\Resources\YearlyDocuments\Pages;

use App\Filament\Resources\YearlyDocuments\YearlyDocumentResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListYearlyDocuments extends ListRecords
{
    protected static string $resource = YearlyDocumentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
