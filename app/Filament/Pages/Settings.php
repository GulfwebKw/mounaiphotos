<?php

namespace App\Filament\Pages;

use App\Models\Status;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use \HackerESQ\Settings\Facades\Settings as config;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Illuminate\Support\Facades\File;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;
use Filament\Forms\Components\Toggle;

class Settings extends Page implements HasForms
{
    use InteractsWithForms;

    protected static ?string $navigationGroup = 'Setting';
    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'filament.pages.settings';


    public $site_title_en ;
    public $site_title ;
    public $email ;
    public $emailNotification ;
    public $telephone ;
    public $work_time ;
    public $work_time_en ;
    public $address_en ;
    public $terms_and_condition_en ;
    public $terms_and_condition ;
    public $address ;
    public $logo ;
    public $twitter ;
    public $tiktok ;
    public $whatsapp ;
    public $facebook ;
    public $snapchat ;
    public $instagram ;
    public $sub_title_en ;
    public $sub_title ;
    public $about_us_en ;
    public $about_us ;
    public $first_status ;
    public $MYFATOORAH_IS_LIVE ;
    public $MYFATOORAH_API_KEY ;
    public $KNET_IS_LIVE ;
    public $KNET_TRANSPORTAL_ID ;
    public $KNET_TRANSPORTAL_PASS ;



    public function mount()
    {
        $this->form->fill(config::get());
    }

    protected function getFormSchema(): array
    {
        return [
            Section::make()
                ->schema([
                    TextInput::make('site_title_en')
                        ->label('Site Title (En)')
                        ->required(),
                    TextInput::make('site_title')
                        ->label('Site Title (Ar)')
                        ->required(),
                    TextInput::make('telephone')
                        ->required(),
                    TextInput::make('email')
                        ->type('email')
                        ->required(),
                    TextInput::make('emailNotification')
                        ->label('Admin email for notification')
                        ->type('email')
                        ->nullable(),
                    TextInput::make('work_time_en')
                        ->hidden()
                        ->nullable(),
                    TextInput::make('work_time')
                        ->hidden()
                        ->nullable(),
                    TextInput::make('address_en')
                        ->hidden()
                        ->nullable(),
                    TextInput::make('address')
                        ->hidden()
                        ->nullable(),
                    RichEditor::make('terms_and_condition_en')
                        ->hidden()
                        ->nullable(),
                    TextInput::make('logo')
                        ->label('Logo')
                        ->type('file')
                        ->rule(['nullable' , 'image']),
                    RichEditor::make('terms_and_condition')
                        ->nullable(),
                ])
                ->columns(2),
            Section::make()
                ->hidden()
                ->label('Content')
                ->schema([
                    RichEditor::make('about_us_en')
                        ->nullable(),
                    RichEditor::make('about_us')
                        ->nullable(),
                    TextInput::make('sub_title_en')
                        ->nullable(),
                    TextInput::make('sub_title')
                        ->nullable(),
                ])
                ->columns(2),
            Section::make()
                ->label('Social')
                ->schema([
                    TextInput::make('twitter')
                        ->type('url')
                        ->nullable(),
                    TextInput::make('snapchat')
                        ->type('url')
                        ->nullable(),
                    TextInput::make('facebook')
                        ->type('url')
                        ->nullable(),
                    TextInput::make('instagram')
                        ->type('url')
                        ->nullable(),
                    TextInput::make('whatsapp')
                        ->label('Whatsapp Number')
                        ->type('url')
                        ->nullable(),
                    TextInput::make('tiktok')
                        ->type('url')
                        ->nullable(),
                ])
                ->columns(2),
            Section::make()
                ->label('My Fatoorah Gateway')
                ->schema([
                    TextInput::make('MYFATOORAH_API_KEY')
                        ->label('Myfatoorah API Key')
                        ->nullable(),
                    Toggle::make('MYFATOORAH_IS_LIVE')
                        ->label('Gateway in live mode? (Danger! In Production do not stay in red position!)')
                        ->inline(false)
                        ->onColor('success')
                        ->offColor('danger'),
                ])
                ->columns(2),
        ];
    }


    public function save()
    {
        $data = $this->form->getState() ;
        $carbon = now();
        if ( $data['logo'] instanceof TemporaryUploadedFile) {
            $logoPath = "public/". $carbon->year .'/'.$carbon->month.'/'.$carbon->day.'/'.'logo.' . $data['logo']->guessExtension();
            $data['logo']->storeAs($logoPath);
            $last_logo_image = config::get('logo');
            config::force()->set(['logo' => $logoPath]);
            File::delete($last_logo_image);
        }
        config::force()->set(collect($data)->except(['logo'])->toArray());
        Notification::make()
            ->title('Settings saved successfully!')
            ->success()
            ->send();
    }
}
