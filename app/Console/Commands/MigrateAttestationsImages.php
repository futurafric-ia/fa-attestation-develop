<?php

namespace App\Console\Commands;

use Domain\Scan\Models\ScanMismatch;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class MigrateAttestationsImages extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'migrate:image_path';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $attestations = ScanMismatch::whereNotNull('image_path')->with('attestationType')->get();

        foreach ($attestations as $attestation) {
            $imagePath = $attestation['image_path'];
            $prefix = explode('_', $imagePath)[0];
            $folderPath = public_path("storage/attestations_images/{$attestation->attestationType->slug}");
            $imagesList = File::files($folderPath);
            $destinationPath = Storage::disk('public')->path("attestations_images/{$attestation->attestationType->slug}/{$prefix}");

            if (! File::isDirectory($destinationPath)) {
                File::makeDirectory($destinationPath, 493, true);
            }

            foreach ($imagesList as $image) {
                $fileName = pathinfo($image, PATHINFO_FILENAME) . ".jpg";
                $newPath = $prefix . "/" . $fileName;

                if (strpos($fileName, $prefix) !== false) {
                    File::copy($folderPath . "/" . $fileName, $destinationPath . "/" . $fileName);
                    $attestation = ScanMismatch::where('image_path', $fileName)->first();

                    if ($attestation !== null) {
                        $attestation->update(['image_path' => $newPath,]);
                    }
                }
            }
        }

        return 0;
    }
}
