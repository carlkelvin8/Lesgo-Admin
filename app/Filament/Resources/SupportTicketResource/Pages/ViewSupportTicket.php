<?php

namespace App\Filament\Resources\SupportTicketResource\Pages;

use App\Filament\Resources\SupportTicketResource;
use App\Models\SupportTicketMessage;
use Filament\Actions;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ViewRecord;
use Illuminate\Support\Facades\Auth;

class ViewSupportTicket extends ViewRecord
{
    protected static string $resource = SupportTicketResource::class;

    protected static string $view = 'filament.resources.support-ticket-resource.pages.view-support-ticket';

    public ?string $replyMessage = '';
    public bool $replyIsInternal = false;

    public function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Textarea::make('replyMessage')
                ->label('')
                ->placeholder('Type your reply...')
                ->required()
                ->rows(2),
            Forms\Components\Toggle::make('replyIsInternal')
                ->label('Internal note'),
        ])->columns(1);
    }

    public function sendQuickReply(): void
    {
        $this->validate([
            'replyMessage' => 'required|string|max:5000',
        ]);

        SupportTicketMessage::create([
            'support_ticket_id' => $this->record->id,
            'user_id' => Auth::id(),
            'message' => $this->replyMessage,
            'is_internal' => $this->replyIsInternal,
        ]);

        $this->record->update(['last_activity_at' => now()]);

        if (in_array($this->record->status, ['open', 'waiting_internal'])) {
            $this->record->update(['status' => 'in_progress']);
        }

        if (!$this->record->first_response_at) {
            $this->record->update(['first_response_at' => now()]);
        }

        $this->replyMessage = '';
        $this->replyIsInternal = false;

        Notification::make()->title('Reply sent')->success()->send();
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
            Actions\DeleteAction::make()->requiresConfirmation(),
            Actions\Action::make('resolve')
                ->label('Resolve')
                ->icon('heroicon-o-check-circle')
                ->color('success')
                ->visible(fn ($record) => !in_array($record->status, ['resolved', 'closed', 'cancelled']))
                ->requiresConfirmation()
                ->action(function () {
                    $this->record->update(['status' => 'resolved', 'resolved_at' => now(), 'last_activity_at' => now()]);
                    Notification::make()->title('Ticket resolved')->success()->send();
                }),
            Actions\Action::make('close')
                ->label('Close')
                ->icon('heroicon-o-x-circle')
                ->color('gray')
                ->visible(fn ($record) => !in_array($record->status, ['closed', 'cancelled']))
                ->requiresConfirmation()
                ->action(function () {
                    $this->record->update(['status' => 'closed', 'closed_at' => now(), 'last_activity_at' => now()]);
                    Notification::make()->title('Ticket closed')->success()->send();
                }),
        ];
    }

    protected function getViewData(): array
    {
        return [
            'messages' => $this->record->messages()->with('user')->orderBy('created_at', 'asc')->get(),
        ];
    }
}
