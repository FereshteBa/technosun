<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage as FacadesStorage;

class ImageController extends Controller
{
    public function localImage($imgUrl){
        $contents = file_get_contents($imgUrl);
        $name = substr($imgUrl, strrpos($imgUrl, '/') + 1);
        FacadesStorage::put($name, $contents);

    }
}
