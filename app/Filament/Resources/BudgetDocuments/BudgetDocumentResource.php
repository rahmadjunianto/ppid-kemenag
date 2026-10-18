<?php

namespace App\Filament\Resources\BudgetDocuments;

use App\Filament\Resources\BudgetDocuments\Pages;
use App\Models\BudgetDocument;
use BackedEnum;
use Filament\Actions;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Table;

class BudgetDocumentResource extends Resource
{
    protected static ?string $model = BudgetDocument::class;

    protected static string | BackedEnum | null $navigationIcon = 'heroicon-o-document-currency-dollar';

    protected static ?string $navigationLabel = 'Kelola DIPA (Anggaran)';

    protected static ?string $modelLabel = 'Dokumen DIPA';

    protected static ?string $pluralModelLabel = 'Daftar Dokumen DIPA';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Informasi DIPA & Unit Kerja')
                    ->description('Atur tahun anggaran, unit kerja / bidang, serta unggah file PDF DIPA.')
                    ->columns(2)
                    ->schema([
                        Forms\Components\TextInput::make('year')
                            ->label('Tahun Anggaran')
                            ->numeric()
                            ->default(now()->year)
                            ->required(),

                        Forms\Components\TextInput::make('code')
                            ->label('Kode Unit Kerja')
                            ->placeholder('Contoh: 01, 02')
                            ->maxLength(20),

                        Forms\Components\TextInput::make('division_name')
                            ->label('Nama Bidang / Bagian / Unit Kerja')
                            ->placeholder('Contoh: Bagian Tata Usaha / Bidang Pendidikan Madrasah')
                            ->required()
                            ->columnSpanFull(),

                        Forms\Components\Textarea::make('description')
                            ->label('Keterangan / Catatan Dokumen (Opsional)')
                            ->rows(2)
                            ->columnSpanFull(),

                        Forms\Components\TextInput::make('sort_order')
                            ->label('Urutan Tampil')
                            ->numeric()
                            ->default(0),
                    ]),

                Section::make('Berkas Dokumen DIPA')
                    ->schema([
                        Forms\Components\FileUpload::make('file_path')
                            ->label('Unggah File PDF DIPA')
                            ->disk('public')
                            ->directory('dipa-documents')
                            ->acceptedFileTypes(['application/pdf'])
                            ->maxSize(20480) // Maks 20 MB
                            ->openable()
                            ->downloadable()
                            ->columnSpanFull(),

                        Forms\Components\TextInput::make('external_url')
                            ->label('Atau Link Eksternal (Opsional)')
                            ->url()
                            ->placeholder('https://...')
                            ->columnSpanFull(),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('year', 'desc')
            ->columns([
                Tables\Columns\TextColumn::make('year')
                    ->label('Tahun')
                    ->badge()
                    ->color('success')
                    ->sortable(),

                Tables\Columns\TextColumn::make('code')
                    ->label('Kode')
                    ->badge()
                    ->searchable(),

                Tables\Columns\TextColumn::make('division_name')
                    ->label('Nama Bidang / Unit Kerja')
                    ->weight('bold')
                    ->searchable()
                    ->wrap(),

                Tables\Columns\TextColumn::make('download_count')
                    ->label('Dilihat/Diunduh')
                    ->badge()
                    ->color('info'),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('year')
                    ->label('Filter Tahun')
                    ->options(fn () => BudgetDocument::select('year')->distinct()->pluck('year', 'year')->toArray()),
            ])
            ->recordActions([
                Actions\EditAction::make(),
                Actions\DeleteAction::make(),
            ])
            ->toolbarActions([
                Actions\BulkActionGroup::make([
                    Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListBudgetDocuments::route('/'),
            'create' => Pages\CreateBudgetDocument::route('/create'),
            'edit' => Pages\EditBudgetDocument::route('/{record}/edit'),
        ];
    }
}