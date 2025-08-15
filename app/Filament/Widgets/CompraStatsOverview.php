<?php

namespace App\Filament\Widgets;

use App\Models\Compra;
use App\Models\User;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class CompraStatsOverview extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make(
                'Compras Pendentes', 
                Compra::where('status', 'pendente')->count() 
            )
            ->description('Aguardando aprovação') 
            ->descriptionIcon('heroicon-m-arrow-trending-up') 
            ->color('warning'), 
            Stat::make(
                'Compras Efetivadas (este mês)',
                Compra::where('status', 'efetivada')->whereMonth('updated_at', now()->month)->count()
            )
            ->description('Finalizadas no mês corrente')
            ->descriptionIcon('heroicon-m-check-circle')
            ->color('success'),
            
            Stat::make(
                'Total de Usuários',
                User::count()
            )
            ->description('Usuários cadastrados no sistema')
            ->descriptionIcon('heroicon-m-users')
            ->color('info'),
        ];
    }
}