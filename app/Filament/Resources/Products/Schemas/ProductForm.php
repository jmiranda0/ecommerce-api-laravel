<?php

namespace App\Filament\Resources\Products\Schemas;

use Illuminate\Support\Str;
use Filament\Schemas\Schema;
use App\Models\Product;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\MarkdownEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Set;

class ProductForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                //
                Section::make('Detalles del Producto')->schema([
                    Select::make('category_id')
                        ->relationship('category', 'name')
                        ->required(),

                    TextInput::make('name')
                        ->required()
                        ->maxLength(255)
                        ->live(onBlur: true) // Generar slug automático al escribir
                        ->afterStateUpdated(fn (string $operation, $state, Set $set) =>
                            $operation === 'create' ? $set('slug', Str::slug($state)) : null
                        ),

                    TextInput::make('slug')
                        ->required()
                        ->disabled() // El usuario no lo edita, se genera solo
                        ->dehydrated() // Pero sí se guarda en la BD
                        ->unique(Product::class, 'slug', ignoreRecord: true),

                    MarkdownEditor::make('description')
                        ->columnSpanFull(),
                ])->columns(2),

                // Sección de Precios e Inventario
                Section::make('Inventario')->schema([
                    TextInput::make('price')
                        ->numeric()
                        ->prefix('$')
                        ->required(),

                    TextInput::make('stock_quantity')
                        ->numeric()
                        ->default(0)
                        ->required(),
                ])->columns(2),

                // Sección de Imágenes
                Section::make('Imágenes')->schema([
                    FileUpload::make('images')
                        ->multiple() // ¡Permite subir muchas fotos!
                        ->directory('products') // Las guarda en storage/app/public/products
                        ->reorderable()
                        ->required(),
                ]),

                // Interruptores
                Toggle::make('is_active')
                    ->default(true),
                Toggle::make('is_featured'),
            ]);
    }
}
