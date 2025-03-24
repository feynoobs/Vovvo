<?php

namespace App\Filament\Widgets;

use Filament\Widgets\ChartWidget;
use App\Models\Group;

class GroupAccess extends ChartWidget
{
    protected static ?string $heading = 'Chart';

    protected function getData(): array
    {

        $access = Group::withCount(['responses'])->get();
        return [
            //
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}
