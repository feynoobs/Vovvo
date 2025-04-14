<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ResponseResource\Pages;
use App\Filament\Resources\ResponseResource\RelationManagers;
use App\Models\Response;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ResponseResource extends Resource
{
    protected static ?string $model = Response::class;
    protected static ?string $navigationLabel = 'レス';
    protected static ?string $modelLabel = 'レス';

    protected static ?int $navigationSort = 4;
    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
                TextColumn::make('id')->label('ID')->sortable(),
                TextColumn::make('name')->label('グループ名'),
                TextColumn::make('sequence')->label('並び順')->sortable(),
                TextColumn::make('created_at')->label('作成日時')->dateTime('Y年m月d日 H:i:s'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                //
                TextColumn::make('id')->label('ID')->sortable(),
                TextColumn::make('name')->label('投稿者名前'),
                TextColumn::make('email')->label('投稿者メアド'),
                TextColumn::make('uid')->label('投稿者ID'),
                TextColumn::make('message')->label('メッセージ')->limit(50),
                TextColumn::make('created_at')->label('作成日時')->dateTime('Y年m月d日 H:i:s'),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('id', 'asc');
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListResponses::route('/'),
            'create' => Pages\CreateResponse::route('/create'),
            'edit' => Pages\EditResponse::route('/{record}/edit'),
        ];
    }
}
