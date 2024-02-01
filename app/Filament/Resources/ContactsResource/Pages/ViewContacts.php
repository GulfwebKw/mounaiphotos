<?php

namespace App\Filament\Resources\ContactsResource\Pages;

use App\Filament\Resources\ContactsResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewContacts extends ViewRecord
{
    protected static string $resource = ContactsResource::class;


    public function mount(int | string $record): void
    {
        parent::mount($record);

        $this->record->read();
    }
}
