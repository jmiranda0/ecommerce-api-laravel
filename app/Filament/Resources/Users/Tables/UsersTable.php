<?php

namespace App\Filament\Resources\Users\Tables;

use App\Enums\UserRole;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class UsersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')->searchable(),
                TextColumn::make('email')->searchable(),
                TextColumn::make('role')
                ->badge()
                ->formatStateUsing(fn (UserRole $state): string => match ($state) {
                    UserRole::ADMIN => 'Administrador',
                    UserRole::CUSTOMER => 'Cliente',
                })
                ->color(fn (UserRole $state): string => match ($state) {
                    UserRole::ADMIN => 'danger',   // Rojo
                    UserRole::CUSTOMER => 'info', // Azul
                })
                ->sortable()
                ,
                //TextColumn::make('created_at')->dateTime()->sortable(),
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
