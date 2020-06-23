<?php

namespace App\Http\Controllers;

use App\Visitante;
use App\Sala;
use App\SalaVisitante;
use Illuminate\Http\Request;

class VisitanteController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');   
    }

    public function out(Request $request, Sala $sala)
    {
    
        /* Marco que o visitante saiu da sala */
        $salaVisitante = SalaVisitante::find($request->sala_visitante);
        $salaVisitante->visitante_status = 'out';
        $salaVisitante->save();

        /* Recupero visitantes ativos na sala para exibir atualizado. */
        
        $salaResultado = SalaVisitante::where('sala_id', '=', $salaVisitante->sala_id)
            ->where('visitante_status', '=', 'in')
            ->join('visitantes', 'visitantes.id', '=', 'sala_visitante.visitante_id')
            ->get();

        $sala = array();

        foreach ($salaResultado as $key => $val) {
            $sala[] = array(
                "id"      => $val->id,
                "visitante" => $val->nome
            );
        }
   
        if (empty($sala)) {
            return response()->json($salaResultado, 204);
            exit;
        }

        return response()->json($sala,201);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {   
        $salas      = Sala::all();
        $visitantes = Visitante::all();
        return view('visitante.index', compact('visitantes', 'salas'));
    }

    /**
     * Verifica quantidade de pessoas ativas na sala.
     */
    public function totalSala($sala_id)
    {
        $total = SalaVisitante::where('sala_id', '=', $sala_id)
                                ->where('visitante_status', '=', 'in')
                                ->count();
        return $total;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $sala = Sala::find($request->sala);
        $capacidadeSala = $sala->capacidade;
        /*
            Verifica quantidade de pessoas tivas na sala 
         */   
        $totalVisitanteSala = $this->totalSala($request->sala);
      
        if($totalVisitanteSala >= $capacidadeSala){
            $retorno = array('retorno'=>false,'limit'=>$capacidadeSala);
            return response()->json($retorno,200);
        }
      
        $visitante = Visitante::create([
            'nome'           => $request->nome, 
            'cpf'            => $request->cpf,
            'email'          => $request->email,
            'nascimento'     => $request->nascimento
        ]); 


        $salaVisitante = SalaVisitante::create([
            'visitante_id'      => $visitante->id,
            'sala_id'           => $request->sala,
            'visitante_status'  => 'in'
        ]);


        $retorno = array(
            "id"            => $visitante->id,
            "nome"          => $visitante->nome,
            "cpf"           => $visitante->cpf,
            "email"         => $visitante->email,
            "nascimento"    => $visitante->nascimento,
            "sala"          => $visitante->sala,
            "sala_status"   => $salaVisitante->visitante_status
        );
 
         return response()->json($retorno,201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Visitante  $visitante
     * @return \Illuminate\Http\Response
     */
    public function show(Visitante $visitante)
    {
        //
    }


}
