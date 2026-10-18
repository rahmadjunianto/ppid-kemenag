<?php

namespace App\Filament\Resources\Menus;

use App\Filament\Resources\Menus\Pages;
use App\Models\Menu;
use BackedEnum;
use Filament\Actions;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Table;

class MenuResource extends Resource
{
    protected static ?string $model = Menu::class;

    protected static string | BackedEnum | null $navigationIcon = 'heroicon-o-bars-3';

    protected static ?string $navigationLabel = 'Manajemen Menu Navbar';

    protected static ?string $modelLabel = 'Menu';

    protected static ?string $pluralModelLabel = 'Kelola Menu';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Forms\Components\TextInput::make('label')
                    ->label('Nama Menu')
                    ->placeholder('Contoh: Profil PPID / Renstra')
                    ->required()
                    ->maxLength(255),

                Forms\Components\TextInput::make('url')
                    ->label('URL / Link Tujuan')
                    ->placeholder('Contoh: /page/arsip/renstra atau https://google.com')
                    ->required()
                    ->maxLength(255),

                Forms\Components\Select::make('parent_id')
                    ->label('Menu Induk (Kosongkan jika ini Menu Utama)')
                    ->options(fn () => Menu::whereNull('parent_id')->pluck('label', 'id'))
                    ->searchable()
                    ->nullable(),

                Forms\Components\TextInput::make('sort_order')
                    ->label('Urutan (Nomor)')
                    ->numeric()
                    ->default(0)
                    ->required(),

                Forms\Components\Toggle::make('is_active')
                    ->label('Aktif / Tampilkan di Navbar')
                    ->default(true)
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('sort_order', 'asc')
            ->columns([
                Tables\Columns\TextColumn::make('sort_order')
                    ->label('Urutan')
                    ->sortable(),

                Tables\Columns\TextColumn::make('label')
                    ->label('Nama Menu')
                    ->searchable()
                    ->weight('bold'),

                Tables\Columns\TextColumn::make('url')
                    ->label('URL Tujuan'),

                Tables\Columns\TextColumn::make('parent.label')
                    ->label('Menu Induk')
                    ->badge()
                    ->color('gray')
                    ->formatStateUsing(fn ($state) => $state ?? 'Menu Utama'),

                Tables\Columns\ToggleColumn::make('is_active')
                    ->label('Aktif'),
            ])
            ->filters([
                Tables\Filters\TernaryFilter::make('is_active')
                    ->label('Status Aktif'),
            ])
            ->recordActions([
                Actions\EditAction::make(),
                Actions\DeleteAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListMenus::route('/'),
            'create' => Pages\CreateMenu::route('/create'),
            'edit' => Pages\EditMenu::route('/{record}/edit'),
        ];
    }
}