<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Intervention\Image\Facades\Image;

class PerfilController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        return view('perfil.index');
    }

    public function store(Request $request)
    {
        //Modificar el Request
        $request->request->add(['username'=>Str::slug($request->username)]);


        $this->validate($request, [
            'username' => ['required', 'unique:users,username,'.auth()->user()->id, 
            'min:3', 'max:20', 'not_in:twitter,editar-perfil']
        ]);

        if($request->imagen){
            $imagen = $request->file('imagen');

            $nombreImagen = Str::uuid() . "." . $imagen->extension(); //generar un id unico a cada imagen
    
            $imagenServidor = Image::make($imagen); //crear una imagen de intervetionImage
            $imagenServidor->fit(1000,1000); //efectos intervetionImagen // cortar a 1000 x 1000
    
            $imagenPath = public_path('perfiles') . '/' . $nombreImagen; //mover algun lugar al servidor
            $imagenServidor -> save($imagenPath);
        }

        //Guardar cambios
        $usuario = User::find(auth()->user()->id);
        $usuario->username = $request->username;
        $usuario->imagen = $nombreImagen ?? auth()->user()->imagen ?? null;
        $usuario->save();

        //Redireccionar
        return redirect()->route('posts.index', $usuario->username);
    }
}
