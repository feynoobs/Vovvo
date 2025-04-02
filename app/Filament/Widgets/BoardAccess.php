<?php

namespace App\Filament\Widgets;

use Filament\Widgets\ChartWidget;
use App\Models\Board;

class BoardAccess extends ChartWidget
{
    protected static ?string $heading = 'BBS毎投稿数';
    protected static ?int $sort = 2;
    protected int|string|array $columnSpan = 12;

    protected function getData(): array
    {
        $boards = Board::withCount(['responses'])->orderBy('id', 'asc')->get();

        return [
            'datasets' => [
                [
                    'label' => '投稿数',
                    'data' => $boards->pluck('responses_count')->toArray(),
                ],
            ],
            'labels' => $boards->pluck('name')->toArray(),
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }

    protected function getView(): string
    {
        return 'filament.widgets.dashboard-chart-widget';
    }

    protected function getOptions(): array
    {
        return [
            'responsive' => true,
            'maintainAspectRatio' => false,
        ];
    }
}
