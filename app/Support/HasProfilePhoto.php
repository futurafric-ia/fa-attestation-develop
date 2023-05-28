<?php

namespace App\Support;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

trait HasProfilePhoto
{
    /**
     * Update the user's profile photo.
     *
     * @param  \Illuminate\Http\UploadedFile  $photo
     * @return void
     */
    public function updateProfilePhoto(UploadedFile $photo)
    {
        tap($this->profile_photo_path, function ($previous) use ($photo) {
            $this->forceFill([
                $this->profilePhotoColumn() => $photo->storePublicly(
                    $this->profilePhotoFolder(),
                    ['disk' => $this->profilePhotoDisk()]
                ),
            ])->save();

            if ($previous) {
                Storage::disk($this->profilePhotoDisk())->delete($previous);
            }
        });
    }

    /**
     * Get the URL to the user's profile photo.
     *
     * @return string
     */
    public function getProfilePhotoUrlAttribute()
    {
        return $this->{$this->profilePhotoColumn()}
                    ? Storage::disk($this->profilePhotoDisk())->url($this->{$this->profilePhotoColumn()})
                    : $this->defaultProfilePhotoUrl();
    }

    /**
     * Get the disk that profile photos should be stored on.
     *
     * @return string
     */
    protected function profilePhotoDisk()
    {
        return isset($_ENV['VAPOR_ARTIFACT_NAME']) ? 's3' : 'public';
    }

    /**
     * Get the default profile photo URL if no profile photo has been uploaded.
     *
     * @return string
     */
    protected function defaultProfilePhotoUrl()
    {
        return 'https://ui-avatars.com/api/?name=' . urlencode($this->full_name) . '&color=7F9CF5&background=EBF4FF';
    }

    /**
     * Get the column that will hold the profile photo url.
     *
     * @return string
     */
    protected function profilePhotoColumn()
    {
        return 'profile_photo_path';
    }

    /**
     * Get the folder that profile photos should be stored in.
     *
     * @return string
     */
    protected function profilePhotoFolder()
    {
        return 'profile-photos';
    }
}
