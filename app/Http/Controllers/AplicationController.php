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
        parent::__construct();
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
}