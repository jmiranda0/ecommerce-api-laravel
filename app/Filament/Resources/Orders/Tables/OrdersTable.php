<?php

namespace App\Filament\Resources\Orders\Tables;

use App\Enums\OrderStatus;
use App\Enums\PaymentStatus;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class OrdersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                // ID del Pedido
                TextColumn::make('id')
                ->label('ID')
                ->searchable()
                ->sortable(),

                // Nombre del Cliente
                TextColumn::make('customer_name')
                    ->label('Cliente')
                    ->searchable(),

                // Total (Formato Dinero)
                TextColumn::make('total_amount')
                    ->label('Total')
                    ->money('USD')
                    ->sortable(),

                // Estado del Pedido (Badge con Colores)
                TextColumn::make('status')
                    ->badge()
                    ->color(fn (OrderStatus $state): string => match ($state) {
                        OrderStatus::NEW->value => 'info',       // Azul
                        OrderStatus::PROCESSING->value => 'warning', // Amarillo
                        OrderStatus::SHIPPED->value => 'success', // Verde
                        OrderStatus::DELIVERED->value => 'success',
                        OrderStatus::CANCELLED->value => 'danger', // Rojo
                        default => 'gray',
                    })
                    ->sortable(),

                // Estado del Pago
                TextColumn::make('payment_status')
                    ->badge()
                    ->color(fn (PaymentStatus $state): string => match ($state) {
                        PaymentStatus::PAID->value => 'success',
                        PaymentStatus::PENDING->value => 'warning',
                        PaymentStatus::FAILED->value => 'danger',
                        default => 'gray',
                    }),

                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
