<?php

namespace App\Filament\Resources\GroupResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Filament\Resources\GroupResource;
use App\Models\Group;

class ListGroups extends ListRecords
{
    protected static string $resource = GroupResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('exportPdf')
                ->label('PDF出力')
                ->icon('heroicon-o-arrow-down-tray')
                ->color('success')
                ->action(function () {
                    $groups = Group::all();
                    $pdf = Pdf::loadView('pdf.group-list', [
                        'groups' => $groups,
                    ]);
                    $pdf->setOptions(['defaultFont' => 'ipaexg']);
                    return response()->streamDownload(function () use ($pdf) {
                        echo $pdf->stream();
                    }, 'groups.pdf');
                }),
            Actions\CreateAction::make(),
        ];
    }
}
