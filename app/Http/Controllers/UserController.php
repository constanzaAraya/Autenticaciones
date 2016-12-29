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
        parent::__construct();
        
        $this->middleware('auth',
            [
                'except' => 
                [
                    'login'
                ]
            ]);
    }

    /**
     * [login description]
     * @param  Request $req [description]
     * @return [type]       [description]
     */
    public function login (Request $req) {
        $mail = $req->input('email');
        $pass = $req->input('password');

        if(Auth::check()) {
            $user = Auth::user();            
            return response()->json([
                'status' => 'ok',
                'token' => $user->token
                ], 200);
        } else {
            $user = User::where('email', $mail)->first();
            $idUser=$user->id;
            if($user) {
                if(Crypt::decrypt($user->password) == $pass) {
                    return response()->json([
                        'status' => 'ok',
                        'id' => $user->id,
                        'name' => $user->nombre,
                        'lastname' => $user->apellido,
                        'email' => $user->email,
                        'token' => $user->token
                    ], 200);
                }
            }
        }        
        return response()->json([
            'status' => 'error',
            'message' => 'Usuario o contraseña incorrectos.'
            ], 400);
    }

    /**
     * [get description]
     * @param  Request $req [description]
     * @param  [type]  $id  [description]
     * @return [type]       [description]
     */

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

    /**
     * [create description]
     * @param  Request $req [description]
     * @return [type]       [description]
     */

    //Crea usuario
    public function create (Request $req) {
        $datos = $req->all();
        $this->validate($req, [
          'nombre' => 'required',
          'apellido' => 'required',
          'email' => 'required|email|unique:users',
          'password' => 'required',
          'cargo' => 'required',
          'departamento' => 'required'
        ]);
        $datos['token'] = md5(uniqid());
        $datos['password'] = Crypt::encrypt($datos['password']);
        
        try{
            $usuario = User::create($datos);
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

    /**
     * [update description]
     * @param  Request $req [description]
     * @param  [type]  $id  [description]
     * @return [type]       [description]
     */

     //Modifica usuario
    public function update (Request $req, $id) {
        $idUser = User::find($id);
        if($idUser){
           $idUser->nombre = $req->input('nombre');
           $idUser->apellido = $req->input('apellido');
           $idUser->email = $req->input('email');
           $idUser->cargo = $req->input('cargo');
           $idUser->departamento = $req->input('departamento');

           $this->validate($req, [
               'nombre' => 'required',
                'apellido' => 'required',
                'email' => 'required|email|unique:users',
                'cargo' => 'required',
                'departamento' => 'required'
           ]);
            /*print_r($idUser->name); print_r($idUser->lastname); print_r($idUser->email);*/
            try {
                $modif=$idUser->save();
                if($modif){
                    return response()->json([
                        'status' => 'ok',
                        'message' => 'Usuario modificado!',
                        'usuario' => $idUser
                    ], 200); 
                }
            } 
            catch (\Illuminate\Database\QueryException $e) {
                return response()->json([
                   'status' => 'Error BD',
                   'message' => 'Problemas en la modificación!'
                ], 500);
            }            
        }
        return response()->json([
            'status' => 'Error:',
            'message' => 'Usuario no existe!'
        ], 404);         
    }

    
    /**
     * [delete description]
     * @param  Request $req [description]
     * @param  [type]  $id  [description]
     * @return [type]       [description]
     */

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
                 $idUser_pass->api_token = md5(uniqid());
                 try {
                    $modPass=$idUser_pass->save();
                    if($modPass){
                        return response()->json([
                            'status' => 'ok',
                            'message' => 'Password de Usuario modificada!',
                            'IDuser' => $idUser_pass->id
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
