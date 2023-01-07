<?php

namespace App\Http\Controllers;

use App\Models\Locacao;
use Illuminate\Http\Request;
use App\Repositories\LocacaoRepository;

class LocacaoController extends Controller
{

    private Locacao $locacao;

    public function __construct(Locacao $locacao)
    {
        $this->locacao = $locacao;
    }

    public function index(Request $request)
    {
        $locacaoRepository = new LocacaoRepository($this->locacao);

        if ($request->has('filtro'))
            $locacaoRepository->filtro($request->filtro);

        if ($request->has('atributos'))
            $locacaoRepository->selectAtributos($request->atributos);


        return response()->json($locacaoRepository->getResultado(), 200);
    }

    public function store(Request $request)
    {
        $request->validate($this->locacao->rules());

        $locacao = $this->locacao->create([
            'cliente_id' => $request->cliente_id,
            'carro_id' => $request->carro_id,
            'data_inicio_periodo' => $request->data_inicio_periodo,
            'data_final_previsto_periodo' => $request->data_final_previsto_periodo,
            'data_final_realizado_periodo' => $request->data_final_realizado_periodo,
            'valor_diaria' => $request->valor_diaria,
            'km_inicial' => $request->km_inicial,
            'km_final' => $request->km_final
        ]);

        return response()->json($locacao, 201);
    }

    public function show($id)
    {
        $locacao = $this->locacao->find($id);

        if ($locacao === null)
            return response()->json([
                'erro' => 'Recurso pesquisado não existe'
            ], 404);

        return response()->json($locacao, 200);
    }

    public function update(Request $request, $id)
    {
        $locacao = $this->locacao->find($id);

        if ($locacao === null)
            return response()->json([
                'erro' => 'Impossível realizar a atualização. O recurso solicitado não existe'
            ], 404);

        if ($request->method() === 'PATCH') {
            $regrasDinamicas = array();

            foreach ($locacao->rules() as $input => $regra) {
                if (array_key_exists($input, $request->all()))
                    $regrasDinamicas[$input] = $regra;
            }

            $request->validate($regrasDinamicas);
        } else
            $request->validate($locacao->rules());

        $locacao->fill($request->all());
        $locacao->save();

        return response()->json($locacao, 200);
    }

    public function destroy($id)
    {
        $locacao = $this->locacao->find($id);

        if ($locacao === null)
            return response()->json([
                'erro' => 'Impossível realizar a exclusão. O recurso solicitado não existe'
            ], 404);

        $locacao->delete();

        return response()->json([
            'msg' => 'A locação foi removida com sucesso'
        ], 200);
    }
}
