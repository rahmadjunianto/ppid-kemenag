<?php

namespace App\Filament\Resources\YearlyDocuments;

use App\Filament\Resources\YearlyDocuments\Pages;
use App\Models\YearlyDocument;
use BackedEnum;
use Filament\Actions;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Table;

class YearlyDocumentResource extends Resource
{
    protected static ?string $model = YearlyDocument::class;

    protected static string | BackedEnum | null $navigationIcon = 'heroicon-o-folder-open';

    protected static ?string $navigationLabel = 'Arsip Dokumen';

    protected static ?string $modelLabel = 'Dokumen Arsip';

    protected static ?string $pluralModelLabel = 'Kelola Arsip Dokumen';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Informasi Dokumen')
                    ->columns(2)
                    ->schema([
                        Forms\Components\Select::make('type')
                            ->label('Kategori Dokumen')
                            ->placeholder('Pilih kategori dokumen...')
                            ->options(function () {
                                // 1. Kategori standar bawaan Kemenag (agar selalu ada walau database kosong)
                                $defaults = [
                                    'renstra' => 'Renstra (Rencana Strategis)',
                                    'bmn' => 'BMN (Barang Milik Negara)',
                                    'statistik' => 'Statistik & Data Publik',
                                    'laporan-keuangan' => 'Laporan Keuangan',
                                    'perjanjian-kinerja' => 'Perjanjian Kinerja',
                                    'laporan-kinerja' => 'Laporan Kinerja (LKJIP)',
                                    'sk-ppid' => 'SK Tim Pengelola PPID',
                                ];

                                // 2. Ambil juga kategori tambahan yang sudah pernah diinput ke database
                                $dbTypes = \App\Models\YearlyDocument::query()
                                    ->select('type')
                                    ->distinct()
                                    ->pluck('type', 'type')
                                    ->mapWithKeys(fn($item) => [$item => ucwords(str_replace(['-', '_'], ' ', $item))])
                                    ->toArray();

                                // Gabungkan keduanya
                                return array_merge($defaults, $dbTypes);
                            })
                            ->searchable()
                            ->getSearchResultsUsing(fn(string $search) => array_filter(
                                array_merge([
                                    'renstra' => 'Renstra (Rencana Strategis)',
                                    'bmn' => 'BMN (Barang Milik Negara)',
                                    'statistik' => 'Statistik & Data Publik',
                                    'laporan-keuangan' => 'Laporan Keuangan',
                                    'perjanjian-kinerja' => 'Perjanjian Kinerja',
                                    'laporan-kinerja' => 'Laporan Kinerja (LKJIP)',
                                    'sk-ppid' => 'SK Tim Pengelola PPID',
                                ], \App\Models\YearlyDocument::query()->select('type')->distinct()->pluck('type', 'type')->toArray()),
                                fn($value) => str_contains(strtolower($value), strtolower($search))
                            ))
                            ->getOptionLabelUsing(fn($value): string => ucwords(str_replace(['-', '_'], ' ', $value)))
                            ->createOptionForm([
                                Forms\Components\TextInput::make('new_type')
                                    ->label('Nama Kategori Baru (Slug)')
                                    ->placeholder('Contoh: draf-kebijakan')
                                    ->required()
                                    ->live(onBlur: true)
                                    ->afterStateUpdated(fn(callable $set, ?string $state) => $set('new_type', \Illuminate\Support\Str::slug($state))),
                            ])
                            ->createOptionUsing(function (array $data): string {
                                return \Illuminate\Support\Str::slug($data['new_type']);
                            })
                            ->helperText('Pilih dari daftar atau buat kategori baru melalui tombol (+).')
                            ->required(),
                        Forms\Components\TextInput::make('year')
                            ->label('Tahun Dokumen')
                            ->numeric()
                            ->default(now()->year)
                            ->required(),

                        Forms\Components\TextInput::make('title')
                            ->label('Judul Dokumen / Laporan')
                            ->required()
                            ->columnSpanFull(),

                        Forms\Components\Textarea::make('description')
                            ->label('Keterangan Ringkas')
                            ->rows(2)
                            ->columnSpanFull(),
                    ]),

                Section::make('Berkas File')
                    ->schema([
                        Forms\Components\FileUpload::make('file_path')
                            ->label('Upload File PDF')
                            ->disk('public')
                            ->directory('yearly-archives')
                            ->acceptedFileTypes(['application/pdf'])
                            ->openable()
                            ->downloadable()
                            ->columnSpanFull(),

                        Forms\Components\TextInput::make('external_url')
                            ->label('Atau Link Eksternal (Google Drive / Looker Studio)')
                            ->url()
                            ->columnSpanFull(),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('year', 'desc')
            ->columns([
                Tables\Columns\TextColumn::make('type')
                    ->label('Kategori')
                    ->badge()
                    ->color('primary')
                    ->formatStateUsing(fn(string $state): string => strtoupper($state)),

                Tables\Columns\TextColumn::make('year')
                    ->label('Tahun')
                    ->badge()
                    ->color('success'),

                Tables\Columns\TextColumn::make('title')
                    ->label('Judul Dokumen')
                    ->searchable()
                    ->weight('bold'),

                Tables\Columns\TextColumn::make('download_count')
                    ->label('Dilihat')
                    ->badge(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('type')
                    ->label('Filter Kategori')
                    ->options([
                        'renstra' => 'Renstra',
                        'bmn' => 'BMN',
                        'statistik' => 'Statistik',
                    ]),
                Tables\Filters\SelectFilter::make('year')
                    ->label('Filter Tahun')
                    ->options(fn() => YearlyDocument::select('year')->distinct()->pluck('year', 'year')->toArray()),
            ])
            ->recordActions([
                Actions\EditAction::make(),
                Actions\DeleteAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListYearlyDocuments::route('/'),
            'create' => Pages\CreateYearlyDocument::route('/create'),
            'edit' => Pages\EditYearlyDocument::route('/{record}/edit'),
        ];
    }
}
