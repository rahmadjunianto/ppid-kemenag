<?php

namespace App\Filament\Resources\BudgetDocuments\Pages;

use App\Filament\Resources\BudgetDocuments\BudgetDocumentResource;
use Filament\Resources\Pages\CreateRecord;

class CreateBudgetDocument extends CreateRecord
{
    protected static string $resource = BudgetDocumentResource::class;
}
