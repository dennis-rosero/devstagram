<?php

namespace App\Http\Controllers;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Intervention\Image\Facades\Image;

class ImagenController extends Controller
{
    public function store(Request $request) 
    {
        $imagen = $request->file('file');

        $nombreImagen = Str::uuid() . "." . $imagen->extension(); //generar un id unico a cada imagen

        $imagenServidor = Image::make($imagen); //crear una imagen de intervetionImage

        $imagenServidor->fit(1000,1000); //efectos intervetionImagen // cortar a 1000 x 1000

        $imagenPath = public_path('uploads') . '/' . $nombreImagen; //mover algun lugar al servidor

        $imagenServidor -> save($imagenPath);

       return response()->json(['imagen'=> $nombreImagen]);
    }
}
