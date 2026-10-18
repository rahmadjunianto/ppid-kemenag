<?php

namespace App\Filament\Resources\YearlyDocuments\Pages;

use App\Filament\Resources\YearlyDocuments\YearlyDocumentResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditYearlyDocument extends EditRecord
{
    protected static string $resource = YearlyDocumentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
