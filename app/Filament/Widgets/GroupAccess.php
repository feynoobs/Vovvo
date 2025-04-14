<?php

namespace App\Filament\Widgets;

use Filament\Widgets\ChartWidget;
use Filament\Forms\Components\Select;
use App\Models\Group;
use App\Enums\Period;

class GroupAccess extends ChartWidget
{
    protected static ?string $heading = 'グループ毎投稿数';
    protected static ?int $sort = 1;
    protected int|string|array $columnSpan = 12;
    protected static ?string $maxHeight = '200px';

    protected function getFormSchema(): array
    {
        return [
            Select::make('period')
                ->label('期間を選択')
                ->options([
                    Period::OneDay   => '1日',
                    Period::OneWeek  => '1週間',
                    Period::OneMonth => '1ヶ月',
                    Period::OneYear  => '1年',
                ])
                ->default(Period::OneDay)
                ->reactive()
                ->afterStateUpdated(function (string $state) {

                }),
        ];
    }

    protected function getData(): array
    {
        $period = $this->filterFormData['period'] ?? Period::OneDay;


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

    protected function getOptions(): array
    {
        return [
            'responsive' => true,
        ];
    }
}
