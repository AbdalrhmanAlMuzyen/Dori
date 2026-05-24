<?php

namespace App\Jobs;

use App\Events\SendFireBaseNotificationEvent;
use App\Models\Company;
use App\Models\Notification;
use App\Models\User;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

class ProcessCompanyJob implements ShouldQueue
{
    use Queueable;

    public $user;
    public $name;
    public $email;
    public $phone_number;
    public $booking_mode;
    public $logo;

    public function __construct($user,$name,$email,$phone_number,$booking_mode,$logo) 
    {
        $this->user = $user;
        $this->name = $name;
        $this->email = $email;
        $this->phone_number = $phone_number;
        $this->booking_mode = $booking_mode;
        $this->logo = $logo;
    }

    public function handle(): void
    {
        $manager = new ImageManager(new Driver());

        $fullPath = Storage::disk('public')->path($this->logo);

        $image = $manager->read($fullPath);

        $image->cover(800, 800);

        $file_name = time() . '.jpg';
        
        $path = 'logos/' . $file_name;

        Storage::disk('public')->put(
            $path,
            (string) $image->toJpeg(80)
        );

        $company = Company::create([
            'user_id'=>$this->user->id,
            'name' => $this->name,
            'email' => $this->email,
            'phone_number' => $this->phone_number,
            'booking_mode' => $this->booking_mode,
            'logo' => $path,
        ]);

        $device_tokens = $this->user->deviceTokens()->where("is_revoked", false)->pluck("fcm_token")->toArray();

        $notification = Notification::create([
            "user_id" => $this->user->id,
            "title" => "Company Created",
            "body" => "The company {$company->name} has been created successfully.",
            "type" => "company"
        ]);

        event(new SendFireBaseNotificationEvent(
            $notification,
            $device_tokens
        ));
    }

    public function failed()
    {

        $device_tokens = $this->user->deviceTokens()->where("is_revoked", false)->pluck("fcm_token")->toArray();

        $notification = Notification::create([
            "user_id" => $this->user->id,
            "title" => "Company Creation Failed",
            "body" => "An error occurred while creating the company. Please try again later.",
            "type" => "company"
        ]);

        event(new SendFireBaseNotificationEvent(
            $notification,
            $device_tokens
        ));
    }
}