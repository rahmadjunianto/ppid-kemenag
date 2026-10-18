<?php

namespace App\Filament\Resources\PublicInformation;

use App\Filament\Resources\PublicInformation\Pages;
use App\Models\PublicInformation;
use BackedEnum;
use Filament\Actions;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Str;
use Filament\Schemas\Components\Utilities\Set;

class PublicInformationResource extends Resource
{
    protected static ?string $model = PublicInformation::class;

    // Standar baru Filament v4 menggunakan BackedEnum|string|null
    protected static string | BackedEnum | null $navigationIcon = 'heroicon-o-document-duplicate';

    protected static ?string $navigationLabel = 'Informasi Publik (PPID)';

    protected static ?string $modelLabel = 'Informasi Publik';

    protected static ?string $pluralModelLabel = 'Daftar Informasi Publik';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Struktur & Identitas Menu')
                    ->description('Atur posisi menu (Kategori Utama A/B/C atau Sub-Menu 1, 2, 3)')
                    ->columns(2)
                    ->schema([
                        Forms\Components\Select::make('classification')
                            ->label('Klasifikasi PPID')
                            ->options([
                                'berkala' => 'Informasi Berkala',
                                'serta_merta' => 'Informasi Serta Merta',
                                'setiap_saat' => 'Informasi Tersedia Setiap Saat',
                                'dikecualikan' => 'Informasi Dikecualikan',
                            ])
                            ->default('berkala')
                            ->required()
                            ->live(),

                        Forms\Components\Select::make('parent_id')
                            ->label('Induk Kategori')
                            ->placeholder('-- Jadikan Kategori Utama (Level Atas) --')
                            ->options(function (Get $get, ?PublicInformation $record) {
                                return PublicInformation::query()
                                    ->where('classification', $get('classification') ?? 'berkala')
                                    ->whereNull('parent_id')
                                    ->when($record, fn(Builder $q) => $q->where('id', '!=', $record->id))
                                    ->orderBy('sort_order')
                                    ->get()
                                    ->mapWithKeys(fn(PublicInformation $item) => [
                                        $item->id => ($item->code ? "{$item->code}. " : '') . $item->title,
                                    ]);
                            })
                            ->searchable()
                            ->preload(),

                        Forms\Components\TextInput::make('code')
                            ->label('Kode / Nomor Urut')
                            ->placeholder('Contoh: A, B, 1, 2, 2.a')
                            ->maxLength(20),

                        Forms\Components\TextInput::make('sort_order')
                            ->label('Urutan Tampil')
                            ->numeric()
                            ->default(0),
                        Forms\Components\TextInput::make('title')
                            ->label('Judul Kategori / Informasi')
                            ->placeholder('Contoh: Ringkasan DIPA / Profil Kanwil')
                            ->required()
                            ->maxLength(255)
                            ->live(onBlur: true)
                            ->afterStateUpdated(fn(Set $set, ?string $state) => $set('slug', Str::slug($state)))
                            ->columnSpanFull(),

                        Forms\Components\Textarea::make('description')
                            ->label('Keterangan Tambahan (Opsional)')
                            ->rows(2)
                            ->columnSpanFull(),

                        Forms\Components\Toggle::make('is_active')
                            ->label('Tampilkan di Halaman Publik')
                            ->default(true)
                            ->inline(false),
                    ]),

                Section::make('Arsip Dokumen / Tautan Multi-Tahun')
                    ->description('Tambahkan file PDF, link eksternal, atau teks halaman. Kosongkan jika menu ini hanya berupa Judul Kategori Induk (A, B, C).')
                    ->schema([
                        Forms\Components\Repeater::make('items')
                            ->relationship('items')
                            ->label('Daftar Dokumen / Tautan')
                            ->orderColumn('sort_order')
                            ->defaultItems(0)
                            ->collapsible()
                            ->itemLabel(
                                fn(array $state): ?string => ($state['label'] ?? 'Item Baru') . (!empty($state['year']) ? " - Tahun {$state['year']}" : '')
                            )
                            ->schema([
                                Grid::make(3)->schema([
                                    Forms\Components\TextInput::make('label')
                                        ->label('Label Tombol')
                                        ->placeholder('Contoh: Tahun 2026 / Lihat Detail')
                                        ->required(),

                                    Forms\Components\Select::make('action_type')
                                        ->label('Jenis Konten')
                                        ->options([
                                            'file' => 'Upload File PDF',
                                            'internal' => 'Link URL (Internal / Halaman Dalam)',
                                            'external' => 'Link URL (Eksternal)',
                                            'content' => 'Teks Halaman / Artikel Langsung',
                                        ])
                                        ->default('file')
                                        ->live()
                                        ->required(),
                                ]),

                                Forms\Components\FileUpload::make('file_path')
                                    ->label('Unggah Dokumen PDF')
                                    ->disk('public')
                                    ->directory('ppid-documents')
                                    ->acceptedFileTypes(['application/pdf'])
                                    ->maxSize(20480)
                                    ->visible(fn(Get $get): bool => $get('action_type') === 'file')
                                    ->required(fn(Get $get): bool => $get('action_type') === 'file')
                                    ->openable()
                                    ->downloadable(),
                                Forms\Components\TextInput::make('internal_url')
                                    ->label('Path / URL Internal')
                                    ->placeholder('Contoh: /page/detail/bmn atau /page/lhkpn')
                                    ->helperText('Masukkan path rute di dalam aplikasi tanpa domain (diawali garis miring /).')
                                    ->required(fn(Get $get): bool => $get('action_type') === 'internal')
                                    ->visible(fn(Get $get): bool => $get('action_type') === 'internal')
                                    ->columnSpanFull(),
                                Forms\Components\TextInput::make('external_url')
                                    ->label('Alamat URL Tujuan')
                                    ->url()
                                    ->placeholder('https://...')
                                    ->visible(fn(Get $get): bool => $get('action_type') === 'link')
                                    ->required(fn(Get $get): bool => $get('action_type') === 'link'),

                                Forms\Components\RichEditor::make('content')
                                    ->label('Isi Konten Halaman')
                                    ->visible(fn(Get $get): bool => $get('action_type') === 'content')
                                    ->required(fn(Get $get): bool => $get('action_type') === 'content')
                                    ->columnSpanFull(),
                                // --- TAMBAHKAN KODE INI TEPAT DI BAWAH RICH EDITOR ---
                                Forms\Components\TextInput::make('map_embed_url')
                                    ->label('Link Embed Google Maps (URL SRC saja)')
                                    ->placeholder('https://www.google.com/maps/embed?pb=...')
                                    ->helperText('Buka Google Maps -> Bagikan -> Sematkan Peta -> Salin hanya link yang ada di dalam tanda petik src="..."')
                                    ->visible(fn(Get $get): bool => $get('action_type') === 'content')
                                    ->columnSpanFull(),
                            ])
                            ->addActionLabel('+ Tambah Dokumen / Tahun Baru'),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('sort_order', 'asc')
            ->reorderable('sort_order')
            ->columns([
                Tables\Columns\TextColumn::make('code')
                    ->label('Kode')
                    ->badge()
                    ->color(fn(PublicInformation $record): string => is_null($record->parent_id) ? 'success' : 'gray')
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('title')
                    ->label('Judul Informasi')
                    ->weight(fn(PublicInformation $record): string => is_null($record->parent_id) ? 'bold' : 'normal')
                    ->description(
                        fn(PublicInformation $record): string =>
                        $record->parent ? "Sub-menu dari: {$record->parent->code}. {$record->parent->title}" : 'Kategori Utama (Induk)'
                    )
                    ->searchable()
                    ->wrap(),

                Tables\Columns\TextColumn::make('classification')
                    ->label('Klasifikasi')
                    ->badge()
                    ->formatStateUsing(fn(string $state): string => match ($state) {
                        'berkala' => 'Berkala',
                        'serta_merta' => 'Serta Merta',
                        'setiap_saat' => 'Setiap Saat',
                        'dikecualikan' => 'Dikecualikan',
                        default => $state,
                    }),

                Tables\Columns\TextColumn::make('items_count')
                    ->counts('items')
                    ->label('Jml File/Link')
                    ->badge()
                    ->color('info'),

                Tables\Columns\IconColumn::make('is_active')
                    ->label('Aktif')
                    ->boolean(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('classification')
                    ->label('Filter Klasifikasi')
                    ->options([
                        'berkala' => 'Informasi Berkala',
                        'serta_merta' => 'Informasi Serta Merta',
                        'setiap_saat' => 'Informasi Setiap Saat',
                        'dikecualikan' => 'Informasi Dikecualikan',
                    ]),

                Tables\Filters\SelectFilter::make('parent_id')
                    ->label('Filter Kategori Induk')
                    ->relationship('parent', 'title', fn(Builder $query) => $query->whereNull('parent_id')),
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
            'index' => Pages\ListPublicInformation::route('/'),
            'create' => Pages\CreatePublicInformation::route('/create'),
            'edit' => Pages\EditPublicInformation::route('/{record}/edit'),
        ];
    }
}
