<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class RegisterController extends Controller
{

    

    public function store() {

        //Validacion
        request()->validate([
            '*' => 'required' //   
        ]);

        User::create([ 
        'name'=> request()->get('nombre'),
        'email'=>request()->get('email'),
        'password'=> bcrypt(request()->get('password')),
        'apellido'=> request()->get('apellido'),
        'direccion'=> request()->get('direccion'),
        'telefono' => request()->get('telefono'),
        'documento' => request()->get('documento'),
        'fecha_nacimiento' => request()->get('fecha_nacimiento'),
        ]);

        // Una vez registrado, lo mandamos al home pero para eso la sesion debe estar iniciada
        $objeto = new LoginController();
        $objeto->login($_REQUEST);
        return redirect('home');
    }
}
