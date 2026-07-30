<?php

namespace App\Filament\Resources;

use App\Filament\Resources\RatingReviewResource\Pages;
use App\Models\RatingReview;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class RatingReviewResource extends Resource
{
    protected static ?string $model = RatingReview::class;

    protected static ?string $navigationIcon = 'heroicon-o-star';

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->with(['user']);
    }
    protected static ?string $navigationGroup = 'Operations';
    protected static ?int $navigationSort = 4;
    protected static ?string $modelLabel = 'Rating & Review';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Select::make('user_id')->relationship('user', 'name')->searchable()->preload()->required(),
            Forms\Components\Select::make('order_id')->relationship('order', 'id')->searchable()->required(),
            Forms\Components\TextInput::make('overall_rating')->numeric()->minValue(1)->maxValue(5)->required(),
            Forms\Components\TextInput::make('driver_rating')->numeric()->minValue(1)->maxValue(5)->nullable(),
            Forms\Components\TextInput::make('service_rating')->numeric()->minValue(1)->maxValue(5)->nullable(),
            Forms\Components\Textarea::make('review_comment')->nullable()->columnSpanFull(),
            Forms\Components\Select::make('status')->options(['pending' => 'Pending', 'approved' => 'Approved', 'rejected' => 'Rejected', 'flagged' => 'Flagged'])->default('approved'),
            Forms\Components\Toggle::make('is_featured'),
            Forms\Components\Toggle::make('is_anonymous'),
            Forms\Components\Textarea::make('business_response')->nullable()->columnSpanFull(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table->columns([
            Tables\Columns\TextColumn::make('user.name')->searchable(),
            Tables\Columns\TextColumn::make('order_id')->label('Order #'),
            Tables\Columns\TextColumn::make('overall_rating')->sortable(),
            Tables\Columns\TextColumn::make('review_comment')->limit(40)->expandable(),
            Tables\Columns\TextColumn::make('status')->badge()->colors(['warning' => 'pending', 'success' => 'approved', 'danger' => 'rejected', 'gray' => 'flagged']),
            Tables\Columns\IconColumn::make('is_featured')->boolean(),
            Tables\Columns\TextColumn::make('created_at')->dateTime()->sortable(),
        ])->filters([
            Tables\Filters\SelectFilter::make('status')->options(['pending' => 'Pending', 'approved' => 'Approved', 'rejected' => 'Rejected', 'flagged' => 'Flagged']),
        ])->actions([Tables\Actions\EditAction::make(), Tables\Actions\DeleteAction::make()])
        ->defaultSort('created_at', 'desc');
    }

    public static function getPages(): array
    {
        return ['index' => Pages\ListRatingReviews::route('/'), 'create' => Pages\CreateRatingReview::route('/create'), 'edit' => Pages\EditRatingReview::route('/{record}/edit')];
    }
}
