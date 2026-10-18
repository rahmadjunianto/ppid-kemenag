<?php

namespace App\Filament\Resources\BudgetDocuments\Pages;

use App\Filament\Resources\BudgetDocuments\BudgetDocumentResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListBudgetDocuments extends ListRecords
{
    protected static string $resource = BudgetDocumentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
