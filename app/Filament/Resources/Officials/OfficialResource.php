<?php

namespace App\Filament\Resources\Officials;

use App\Filament\Resources\Officials\Pages;
use App\Models\Official;
use BackedEnum;
use Filament\Actions;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Table;

class OfficialResource extends Resource
{
    protected static ?string $model = Official::class;

    protected static string | BackedEnum | null $navigationIcon = 'heroicon-o-user-group';

    protected static ?string $navigationLabel = 'Pejabat & LHKPN';

    protected static ?string $modelLabel = 'Pejabat Struktural';

    protected static ?string $pluralModelLabel = 'Daftar Pejabat & LHKPN';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Biodata Pejabat Struktural')
                    ->columns(2)
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->label('Nama Lengkap & Gelar')
                            ->placeholder('Dr. AKHMAD SRUJI BAHTIAR, M.Pd.I')
                            ->required()
                            ->maxLength(255),

                        Forms\Components\TextInput::make('nip')
                            ->label('NIP')
                            ->placeholder('197204122000031002')
                            ->maxLength(30),

                        Forms\Components\TextInput::make('position')
                            ->label('Jabatan Saat Ini')
                            ->placeholder('KEPALA KANTOR WILAYAH')
                            ->required()
                            ->columnSpanFull(),

                        Forms\Components\TextInput::make('sort_order')
                            ->label('Urutan Hierarki Jabatan')
                            ->numeric()
                            ->default(0)
                            ->helperText('1 = Kakanwil, 2 = Kabag TU, 3 dst = Para Kabid'),

                        Forms\Components\Toggle::make('is_active')
                            ->label('Pejabat Aktif')
                            ->default(true)
                            ->inline(false),
                    ]),

                Section::make('Dokumen LHKPN Per Tahun')
                    ->description('Tambahkan bukti lapor LHKPN pejabat ini untuk setiap tahun (2024, 2025, 2026, dst).')
                    ->schema([
                        Forms\Components\Repeater::make('lhkpns')
                            ->relationship('lhkpns')
                            ->label('Riwayat LHKPN')
                            ->defaultItems(0)
                            ->collapsible()
                            ->itemLabel(fn (array $state): ?string => 'LHKPN Tahun ' . ($state['year'] ?? ''))
                            ->schema([
                                Grid::make(2)->schema([
                                    Forms\Components\TextInput::make('year')
                                        ->label('Tahun Pelaporan LHKPN')
                                        ->numeric()
                                        ->default(now()->year)
                                        ->required(),

                                    Forms\Components\TextInput::make('position_at_year')
                                        ->label('Jabatan pada Tahun Tersebut (Opsional)')
                                        ->placeholder('Kosongkan jika sama dengan jabatan saat ini'),
                                ]),

                                Forms\Components\FileUpload::make('file_path')
                                    ->label('Upload PDF Pengumuman LHKPN')
                                    ->disk('public')
                                    ->directory('lhkpn-documents')
                                    ->acceptedFileTypes(['application/pdf'])
                                    ->maxSize(10240)
                                    ->openable()
                                    ->downloadable(),

                                Forms\Components\TextInput::make('external_url')
                                    ->label('Atau Link Eksternal (Opsional jika tidak upload PDF)')
                                    ->url()
                                    ->placeholder('https://...'),
                            ])
                            ->addActionLabel('+ Tambah LHKPN Tahun Baru'),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('sort_order', 'asc')
            ->reorderable('sort_order')
            ->columns([
                Tables\Columns\TextColumn::make('sort_order')
                    ->label('No')
                    ->sortable(),

                Tables\Columns\TextColumn::make('name')
                    ->label('Nama')
                    ->weight('bold')
                    ->searchable(),

                Tables\Columns\TextColumn::make('nip')
                    ->label('NIP')
                    ->searchable()
                    ->copyable(),

                Tables\Columns\TextColumn::make('position')
                    ->label('Jabatan')
                    ->searchable()
                    ->wrap(),

                Tables\Columns\TextColumn::make('lhkpns.year')
                    ->label('Tahun LHKPN Tersedia')
                    ->badge()
                    ->color('success'),

                Tables\Columns\IconColumn::make('is_active')
                    ->label('Aktif')
                    ->boolean(),
            ])
            ->recordActions([
                Actions\EditAction::make(),
                Actions\DeleteAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListOfficials::route('/'),
            'create' => Pages\CreateOfficial::route('/create'),
            'edit' => Pages\EditOfficial::route('/{record}/edit'),
        ];
    }
}