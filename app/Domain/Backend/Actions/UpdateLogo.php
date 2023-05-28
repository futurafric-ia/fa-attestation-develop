<?php

namespace Domain\Backend\Actions;

use Illuminate\Support\Facades\File;

class UpdateLogo
{
    public function execute(array $data)
    {
        $destinationPath = 'static/images/';
        $fileName = "logo_saham.jpg";
        File::delete($destinationPath . '/' . $fileName);

        request()->file($data['logo'])->move(public_path() . DIRECTORY_SEPARATOR . $destinationPath, $fileName);
    }
}
