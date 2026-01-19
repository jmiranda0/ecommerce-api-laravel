<?php

namespace App\Filament\Resources\Orders\Schemas;

use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class OrderForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                // SECCIÓN 1: DATOS DEL CLIENTE Y ESTADO
                Section::make('Detalles del Pedido')
                    ->schema([
                        TextInput::make('customer_name')->disabled(),
                        TextInput::make('customer_email')->disabled(),

                        // Aquí SÍ dejamos editar el estado para que tú lo actualices
                        Select::make('status')
                            ->options([
                                'new' => 'Nuevo',
                                'processing' => 'Procesando',
                                'shipped' => 'Enviado',
                                'delivered' => 'Entregado',
                                'cancelled' => 'Cancelado',
                            ])
                            ->required(),

                        Select::make('payment_status')
                            ->options([
                                'pending' => 'Pendiente',
                                'paid' => 'Pagado',
                                'failed' => 'Fallido',
                            ])
                            ->required(),
                ])->columns(2),

                // SECCIÓN 2: DIRECCIÓN
                Section::make('Dirección de Envío')
                    ->schema([
                        Textarea::make('address')->disabled()->columnSpanFull(),
                        TextInput::make('city')->disabled(),
                        TextInput::make('zip_code')->disabled(),
                        TextInput::make('customer_phone')->disabled(),
                    ])->columns(3)->collapsed(), // Colapsado para que no ocupe tanto espacio

                // SECCIÓN 3: PRODUCTOS COMPRADOS (Relación HasMany)
                // Filament es tan potente que puede mostrar la relación 'items' así:
                Repeater::make('items')
                    ->relationship()
                    ->schema([
                        Select::make('product_id')
                            ->relationship('product', 'name')
                            ->disabled()
                            ->label('Producto'),

                        TextInput::make('quantity')
                            ->disabled()
                            ->label('Cantidad'),

                        TextInput::make('unit_price')
                            ->disabled()
                            ->prefix('$')
                            ->label('Precio Unitario'),

                        TextInput::make('total_price')
                            ->disabled()
                            ->prefix('$')
                            ->label('Subtotal'),
                    ])
                    ->columns(4)
                    ->deletable(false) // No borrar historial
                    ->addable(false)   // No agregar cosas falsas
                    ->columnSpanFull(),
                    ]);
    }
}
