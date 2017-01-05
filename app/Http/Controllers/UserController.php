<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //parent::__construct();
        
        $this->middleware('auth',
            [
                'except' => 
                [
                    'login'
                ]
            ]);
    }
    
    public function login (Request $req) {
        $mail = $req->input('email');
        $pass = $req->input('password');

        if(Auth::check()) {
            $users = Auth::user();
            return response()->json([
                'status' => 'ok',
                'token' => $users->token
            ], 200);
        } else {
            $users = User::where('email', $mail)->first();
            if($users){
                if(Crypt::decrypt($users->password) == $pass) {
                    $apps = $users->aplications()->get();
                    //print_r($apps);
                    $permisos = array();
                    
                    $count=0;
                    foreach ($users->aplications as $app) {
                        $count++;
                        $id=$app->id;
                        
                        array_push($permisos, array(
                                'id' => $app->id, 
                                'alias' => $app->alias,
                                'url' => $app->url_app,
                                    'permisos' => [
                                        'Estado' => $app->pivot->activo,
                                        'Verificacion' => $app->pivot->primerLogin
                                ]
                            ));
                    }

                    if($count>0){
                            return response()->json([
                                'status' => 'ok',
                                'usuario' => $users,
                                'app' => $permisos
                            ], 200);
                        }else{
                            return response()->json([
                                'status' => 'ok',
                                'id' => $users,
                                'app' => 'No posee aplicaciones'
                            ], 200);
                        }    
                }
            }        
            return response()->json([
                'status' => 'error',
                'message' => 'Usuario o contraseña incorrectos.'
            ], 400);
        }
    }

    //Obtiene datos de usuario 
    public function get (Request $req, $id) {
        $usuario = User::find($id);
        if($usuario) {
            $pass=Crypt::decrypt($usuario->password);
            //return response()->json($usuario, 200);
            return response()->json([
                'usuario' => $usuario,
                //'pass' => $pass
            ], 200);
        }
        return response()->json([
           'error' => 'Usuario no encontrado'
        ], 404);
    }

    //Crea usuario
    public function create (Request $req) {
        $datos = $req->all();
        $this->validate($req, [
          'nombre' => 'required',
          'apellido' => 'required',
          'email' => 'required|email|unique:usuarios',
          'password' => 'required',
          'cargo' => 'required',
          'departamento' => 'required'
        ]);
        $datos['token'] = md5(uniqid());
        $datos['password'] = Crypt::encrypt($datos['password']);
        $usuario = User::create($datos);
        try{            
            return response()->json([
                'status' => 'ok',
                'message' => 'Usuario creado!',
                'usuario' => $usuario
                ], 200);
        }
        catch(\Illuminate\Database\QueryException $e) {
            return response()->json([
               'status' => 'Error BD',
               'message' => 'Problemas al crear registro!'
            ], 500);
        }
    }


     //Modifica usuario
    public function update (Request $req, $id) {
        $usuario = User::find($id);
        if($usuario){
           $usuario->nombre = $req->input('nombre');
           $usuario->apellido = $req->input('apellido');
           $usuario->email = $req->input('email');
           $usuario->cargo = $req->input('cargo');
           $usuario->departamento = $req->input('departamento');
           
           $this->validate($req, [
                'nombre' => 'required',
                'apellido' => 'required',
                'email' => 'required|email|unique:usuarios',
                'cargo' => 'required',
                'departamento' => 'required'
           ]);
            try {
                $modifica=$usuario->save();
                if($modifica){
                    return response()->json([
                        'status' => 'ok',
                        'message' => 'Usuario modificado!',
                        'usuario' => $usuario
                    ], 200); 
                }
            } 
            catch (\Illuminate\Database\QueryException $e) {
                return response()->json([
                   'status' => 'Error BD',
                   'message' => 'Problemas en la modificacion!'
                ], 500);
            }            
        }
        return response()->json([
            'status' => 'Error:',
            'message' => 'Usuario no existe!'
        ], 404);         
    }

    //elimina usuario
    public function delete (Request $req, $id) {
        $idsUser = User::find($id);
        if($idsUser){            
            try{
                if($idsUser->delete()){
                    return response()->json([
                        'status' => 'ok',
                        'message' => 'Usuario eliminado!',
                        'usuario' => $idsUser
                    ], 200); 
                }
            }
            catch (\Illuminate\Database\QueryException $e) {
                return response()->json([
                   'status' => 'Error BD',
                   'message' => 'Problemas al eliminar registro!'
                ], 500);
            }        
        }
        return response()->json([
            'status' => 'Error:',
            'message' => 'Usuario no existe!'
        ], 404);
    }

    public function pass (Request $req, $id) {
        $idUser_pass = User::find($id);
        if($idUser_pass){
           $password = $req->input('pass'); 
           $pass1 = $req->input('new_pass1');
           $pass2 = $req->input('new_pass2');
           $this->validate($req, [
               'pass' =>'required',
               'new_pass1' =>'required',
               'new_pass2' =>'required',
           ]);

           if(Crypt::decrypt($idUser_pass->password) == $password) {
              if($pass1==$pass2){
                 $idUser_pass->password = Crypt::encrypt($pass1);
                 $idUser_pass->token = md5(uniqid());
                 $modPass=$idUser_pass->save();
                 try {
                    $modPass=$idUser_pass->save();
                    if($modPass){
                        return response()->json([
                            'status' => 'ok',
                            'IDuser' => $idUser_pass->id,
                            'message' => 'Password de Usuario modificada!'
                        ], 200); 
                    }
                 } 
                 catch (\Illuminate\Database\QueryException $e) {
                    return response()->json([
                    'status' => 'Error BD',
                    'message' => 'Problemas al modificar contraseña!'
                    ], 500);
                 }
              }
              else{
                return response()->json([
                    'status' => 'Error BD',
                    'message' => 'Password1 no coincide con password2!'
                    ], 500);
              }
           }else{
                return response()->json([
                    'status' => 'Error BD',
                    'message' => 'Clave anterior incorrecta!'
                    ], 500);
           }
        }
        return response()->json([
            'status' => 'Error:',
            'message' => 'Usuario no existe!'
        ], 404);
    }

    public function listUser (Request $req){
        $usuarios = User::all();
        return response()->json($usuarios, 200);
    }
}
