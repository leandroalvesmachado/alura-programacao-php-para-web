<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

use App\Serie;
use App\Temporada;
use App\Episodio;
use App\User;

use App\Http\Requests\SeriesFormRequest;

use App\Services\CriadorDeSerie;
use App\Services\RemovedorDeSerie;

use App\Events\NovaSerie;

class SeriesController extends Controller
{

    public function __construct()
    {
        // $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // $series = Serie::all();
        $series = Serie::query()->orderBy('nome')->get();
        $mensagem = $request->session()->get('mensagem');

        return view('series.index', compact('series', 'mensagem'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('series.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(SeriesFormRequest $request, CriadorDeSerie $criadorDeSerie)
    {
        // salva o upload
        $capa = null;
        if ($request->hasFile('capa')) {
            $capa = $request->file('capa')->store('serie');
        }

        $serie = $criadorDeSerie->criarSerie(
            $request->nome,
            $request->qtd_temporadas,
            $request->ep_por_temporada,
            $capa
        );

        // executando evento
        $eventoNovaSerie = new NovaSerie(
            $request->nome,
            $request->qtd_temporadas,
            $request->ep_por_temporada
        );

        event($eventoNovaSerie);

        // jogando codigo para o listener
        // usuario logado sempre vem no request
        // $user = $request->user();
        // $users = User::all();

        // foreach ($users as $indice => $user) {

        //     $multiplicador = $indice + 1;

        //     $email = new \App\Mail\NovaSerie(
        //         $request->nome,
        //         $request->qtd_temporadas,
        //         $request->ep_por_temporada
        //     );

        //     $email->subject = 'Nova Série Adicionada';

        //     // enviando email sem fila
        //     // Mail::to($user)->send($email);

        //     // envia 1 email a cada 5 segundos
        //     // nao viavel
        //     // sleep(5);

        //     // enviando email com fila
        //     // Mail::to($user)->queue($email);

        //     // enviando email com delay
        //     $delay = now()->addSecond($multiplicador * 5);
        //     Mail::to($user)->later($delay, $email);
        // }

        $request->session()->flash(
            'mensagem',
            "Série {$serie->nome} e suas temporadas e episódios criados com sucesso."
        );

        return redirect()->route('listar_series');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, RemovedorDeSerie $removedorDeSerie)
    {
        $nomeSerie = $removedorDeSerie->removerSerie($request->id);

        $request->session()->flash(
            "mensagem",
            "Série $nomeSerie deletada com sucesso"
        );

        return redirect()->route('listar_series');
    }

    public function editaNome(Request $request, int $id)
    {
        $novoNome = $request->nome;
        $serie = Serie::find($id);
        $serie->nome = $novoNome;
        $serie->save();
    }
}
