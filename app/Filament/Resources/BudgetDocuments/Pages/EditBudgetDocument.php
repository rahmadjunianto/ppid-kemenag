<?php

namespace App\Filament\Resources\BudgetDocuments\Pages;

use App\Filament\Resources\BudgetDocuments\BudgetDocumentResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditBudgetDocument extends EditRecord
{
    protected static string $resource = BudgetDocumentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
