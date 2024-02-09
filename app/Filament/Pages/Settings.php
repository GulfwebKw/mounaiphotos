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


    protected $gateways  = ['payle8']; // MYFATOORAH , payle8

    public $site_title_en ;
    public $site_title ;
    public $email ;
    public $emailNotification ;
    public $telephoneNotification ;
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
    public $DezSMS_user_id ;
    public $DezSMS_sender_name ;
    public $DezSMS_api_key ;
    public $PayLeMerchantAccountID ;
    public $PayLe_IS_LIVE ;
    public $PayLeAccountPassword ;
    public $PayLeResourceKey ;
    public $PayLeBusinessCode ;



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
                    TextInput::make('telephoneNotification')
                        ->label('Admin Phone for notification')
                        ->type('number')
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
                ->schema( $this->Socials() )
                ->columns(2),
            Section::make()
                ->label('Payment Gateway')
                ->schema( $this->PaymentGateway())
                ->columns(2),
            Section::make()
                ->label('Dez SMS')
                ->schema($this->DezSMS())
                ->columns(2),
        ];
    }

    public function PaymentGateway() {
        $fields = [] ;
        if ( in_array( 'MYFATOORAH'  , $this->gateways )) {
            $fields[]  = TextInput::make('MYFATOORAH_API_KEY')
                ->label('Myfatoorah API Key')
                ->nullable();
            $fields[] = Toggle::make('MYFATOORAH_IS_LIVE')
                        ->label('Gateway in live mode? (Danger! In Production do not stay in red position!)')
                        ->inline(false)
                        ->onColor('success')
                        ->offColor('danger');
        }
        if ( in_array( 'payle8'  , $this->gateways )) {
            $fields[]  =  TextInput::make('PayLeMerchantAccountID')
                ->label('PayLe Merchant Account ID')
                ->nullable();
            $fields[]  =  TextInput::make('PayLeAccountPassword')
                ->label('PayLe Account Password')
                ->nullable();
            $fields[]  =  TextInput::make('PayLeResourceKey')
                ->label('PayLe Resource Key')
                ->nullable();
            $fields[]  =  TextInput::make('PayLeBusinessCode')
                ->label('PayLe Business Code')
                ->nullable();
            $fields[] = Toggle::make('PayLe_IS_LIVE')
                        ->label('Gateway in live mode? (Danger! In Production do not stay in red position!)')
                        ->inline(false)
                        ->onColor('success')
                        ->offColor('danger');
        }

        return $fields;
    }

    public function DezSMS() {
        return [
            TextInput::make('DezSMS_user_id')
                ->label('Dez SMS User Id')
                ->nullable(),
            TextInput::make('DezSMS_sender_name')
                ->label('Dez SMS Sender name')
                ->nullable(),
            TextInput::make('DezSMS_api_key')
                ->label('Dez SMS API key')
                ->nullable(),
        ];
    }

    public function Socials() {
        return [
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
