<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\ImageManager;

class ProcessCompanyLogoJob implements ShouldQueue
{
    use Queueable;

    public object $company;
    public string $logo_path;

    public function __construct(object $company , string $logo_path)
    {
        $this->company=$company;
        $this->logo_path=$logo_path;
    }

    public function handle(): void
    {
        $manager = new ImageManager(new Driver());

        $fullPath = Storage::disk('public')->path($this->logo_path);

        $logo = $manager->read($fullPath);

        $logo->cover(800, 800);

        $file_name = time() . '.jpg';
        $path = 'logos/' . $file_name;

        Storage::disk('public')->put(
            $path,
            (string) $logo->toJpeg(80)
        );
        
        $this->company->update([
            "logo_path"=>$path
        ]);
    }
}
