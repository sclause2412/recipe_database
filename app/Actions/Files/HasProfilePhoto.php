<?php

namespace App\Actions\Files;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\ImageManagerStatic as Image;


trait HasProfilePhoto
{
    /**
     * Update the user's profile photo.
     *
     * @param  \Illuminate\Http\UploadedFile  $photo
     * @param  string  $storagePath
     * @return void
     */
    public function updateProfilePhoto(UploadedFile $photo)
    {
        $size = config('image.profile.size', 200);
        $path = config('image.profile.path', 'profile-photos');

        $imgSource = Image::make($photo->path());
        $f = max($size / $imgSource->width(), $size / $imgSource->height());
        $imgSource->resize($imgSource->width() * $f, $imgSource->height() * $f, function ($constraint) {
            $constraint->aspectRatio();
        });
        $imgSource->crop($size, $size);

        $destFile = $path . '/' . md5(uniqid()) . '.png';

        $fp = Storage::path('public/' . $destFile);
        Storage::createDirectory('public/' . $path);
        $imgSource->save($fp, 100, 'png');


        if (!is_null($this->profile_photo_path)) {
            Storage::delete('public/' . $this->profile_photo_path);
        }

        $this->forceFill([
            'profile_photo_path' => $destFile,
        ])->save();
    }

    /**
     * Delete the user's profile photo.
     *
     * @return void
     */
    public function deleteProfilePhoto()
    {
        if (is_null($this->profile_photo_path)) {
            return;
        }

        Storage::delete('public/' . $this->profile_photo_path);

        $this->forceFill([
            'profile_photo_path' => null,
        ])->save();
    }

    /**
     * Get the URL to the user's profile photo.
     *
     * @return \Illuminate\Database\Eloquent\Casts\Attribute
     */
    public function profilePhotoUrl(): Attribute
    {
        return Attribute::get(function () {
            if (is_null($this->profile_photo_path))
                $this->createDefaultProfilePhoto();

            if (!Storage::exists('public/' . $this->profile_photo_path))
                $this->createDefaultProfilePhoto();

            return Storage::url($this->profile_photo_path);
        });
    }

    /**
     * Get the default profile photo URL if no profile photo has been uploaded.
     *
     * @return void
     */
    protected function createDefaultProfilePhoto()
    {

        $path = config('image.profile.path', 'profile-photos');
        $destFile = $path . '/' . md5(uniqid()) . '.png';

        $initials = substr(replace_umlaut($this->firstname), 0, 1) . substr(replace_umlaut($this->lastname), 0, 1);
        if ($initials == '')
            $initials = substr(replace_umlaut($this->name), 0, 1);

        $size = config('image.profile.size', 200);
        $h = random_float(0, 360);

        $background = RGBtoHEX(HSVtoRGB($h, random_float(10, 20), 100));
        $color = RGBtoHEX(HSVtoRGB($h, 100, random_float(50, 100)));

        $img = Image::canvas($size, $size, $background);

        $img->text(strtoupper($initials), intval($size / 2), intval($size / 2), function ($font) use ($size, $color) {
            $font->file(resource_path('fonts/Gontserrat-Regular.ttf'));
            $font->size(intval($size / 2));
            $font->align('center');
            $font->valign('middle');
            $font->color($color);
        });


        $fp = Storage::path('public/' . $destFile);
        Storage::createDirectory('public/' . $path);
        $img->save($fp, 100, 'png');


        $this->forceFill([
            'profile_photo_path' => $destFile,
        ])->save();

    }
}