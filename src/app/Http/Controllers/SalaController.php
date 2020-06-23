<?php

namespace App\Http\Controllers;

use App\Sala;
use App\SalaVisitante;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class SalaController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');   
    }
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('salas.index');
    }


    public function listarSalas(){
        $salas = Sala::leftJoin('sala_visitante', 'sala_visitante.sala_id', '=', 'salas.id')
        ->select(['salas.*', DB::raw('(SELECT COUNT(*) FROM `sala_visitante` WHERE `sala_visitante`.`sala_id` = `salas`.`id` AND 
        `sala_visitante`.`visitante_status` = "in") as visitantes')
        ])->groupBy('salas.id')->get();

        return response()->json($salas, 201);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $sala = Sala::create([
            'sala' => $request->sala,
            'capacidade' => $request->capacidade
        ]);

        return response()->json($sala, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Sala  $sala
     * @return \Illuminate\Http\Response
     */
    public function show(Sala $sala, Request $request)
    {

        $salaResultado = SalaVisitante::where('sala_id', '=', $request->sala_id)
            ->where('visitante_status', '=', 'in')
            ->join('visitantes', 'visitantes.id', '=', 'sala_visitante.visitante_id')
            ->get();

        $sala = array();

        foreach ($salaResultado as $key => $val) {
            $sala[] = array(
                "sala"      => $val->id,
                "visitante" => $val->nome
            );
        }
   
        if (empty($sala)) {
            return response()->json($salaResultado, 204);
            exit;
        }

        return response()->json($sala, 200);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Sala  $sala
     * @return \Illuminate\Http\Response
     */
    public function edit(Sala $sala)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Sala  $sala
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Sala $sala)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Sala  $sala
     * @return \Illuminate\Http\Response
     */
    public function destroy(Sala $sala)
    {
        //
    }
}
