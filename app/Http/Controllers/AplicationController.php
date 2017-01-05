<?php

namespace App\Http\Controllers;

use App\Aplication;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Auth;

class AplicationController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //parent::__construct();
    }

    //Obtiene datos de aplicacion 
    public function get (Request $req, $cod) {
        $apl = Aplication::find($cod);
        if($apl) {
            return response()->json([
                'aplicacion' => $apl
            ], 200);
        }
        return response()->json([
           'error' => 'Aplicacion no encontrada!'
        ], 404);
    }

    //Obtiene lista de aplicaciones
    public function listApl (Request $req){
        $aplicaciones = Aplication::all();
        return response()->json($aplicaciones, 200);
    }

    //Crea tipo_aplicacion
    public function createApp (Request $req) {
        $datos = $req->all();
        $this->validate($req, [
          'nombre' => 'required',
          'alias' => 'required',
          'url_app' => 'required',
          'url_api' => 'required'
        ]);
        $app_ = Aplication::create($datos);
        try{            
            return response()->json([
                'status' => 'ok',
                'message' => 'Aplicacion creada!',
                'App' => $app_
                ], 200);
        }
        catch(\Illuminate\Database\QueryException $e) {
            return response()->json([
               'status' => 'Error BD',
               'message' => 'Problemas al crear registro!'
            ], 500);
        }
    }

    //Modifica tipo_aplicacion
    public function updateApp (Request $req, $id) {
        $app_ = Aplication::find($id);
        if($app_){
           $app_->nombre = $req->input('nombre');
           $app_->alias = $req->input('alias');
           $app_->url_app = $req->input('url_app');
           $app_->url_api = $req->input('url_api');
           
           $this->validate($req, [
                'nombre' => 'required',
                'alias' => 'required',
                'url_app' => 'required',
                'url_api' => 'required'
           ]);
            try {
                $modifica=$app_->save();
                if($modifica){
                    return response()->json([
                        'status' => 'ok',
                        'message' => 'Aplicacion modificada!',
                        'usuario' => $app_
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
            'message' => 'Aplicacion no existe!'
        ], 404);         
    }


    //elimina tipo_aplicacion
    public function deleteApp (Request $req, $id) {
        $idsApp = Aplication::find($id);
        if($idsApp){            
            try{
                if($idsApp->delete()){
                    return response()->json([
                        'status' => 'ok',
                        'message' => 'Aplicacion eliminada!',
                        'usuario' => $idsApp
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
            'message' => 'Aplicacion no existe!'
        ], 404);
    }
}