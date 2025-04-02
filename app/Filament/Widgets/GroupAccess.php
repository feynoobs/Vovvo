<?php

namespace App\Filament\Widgets;

use Filament\Widgets\ChartWidget;
use App\Models\Group;

class GroupAccess extends ChartWidget
{
    protected static ?string $heading = 'グループ毎投稿数';
    protected static ?int $sort = 1;
    protected int|string|array $columnSpan = 12;
    protected static ?string $maxHeight = '200px';

    protected function getData(): array
    {
        $groups = Group::withCount(['responses'])->orderBy('id', 'asc')->get();

        return [
            'datasets' => [
                [
                    'label' => '投稿数',
                    'data' => $groups->pluck('responses_count')->toArray(),
                ],
            ],
            'labels' => $groups->pluck('name')->toArray(),
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
        ];
    }
}
