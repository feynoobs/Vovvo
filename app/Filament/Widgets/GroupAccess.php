<?php

namespace App\Filament\Widgets;

use Filament\Widgets\ChartWidget;
use App\Models\Group;

class GroupAccess extends ChartWidget
{
    protected static ?string $heading = 'Chart';

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
}
