<?php

namespace App\Http\Controllers;

use App\Models\Marca;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class MarcaController extends Controller
{
    private Marca $marca;

    public function __construct(Marca $marca)
    {
        $this->marca = $marca;
    }

    public function index()
    {
        $marcas = $this->marca->all();

        return response()->json($marcas, 200);
    }

    public function store(Request $request)
    {
        $request->validate($this->marca->rules(), $this->marca->feedback());

        $imagem = $request->file('imagem');
        $imagem_urn = $imagem->store('imagens', 'public');

        $marca = $this->marca->create([
            "nome" => $request->input('nome'),
            "imagem" => $imagem_urn
        ]);

        return response()->json($marca, 201);
    }

    public function show($id)
    {
        $marca = $this->marca->find($id);

        if ($marca === null) {
            return response()->json([
                'erro' => 'Recurso pesquisado não existe'
            ], 404);
        }

        return response()->json($marca, 200);
    }

    public function update(Request $request, $id)
    {
        $marca = $this->marca->find($id);

        if ($marca === null) {
            return response()->json(['erro' => 'Impossível realizar a atualização. O recurso solicitado não existe'], 404);
        }

        if ($request->method() === 'PATCH') {
            $regrasDinamicas = [];

            foreach ($marca->rules() as $input => $regra) {
                if (array_key_exists($input, $request->all()))
                    $regrasDinamicas[$input] = $regra;
            }

            $request->validate($regrasDinamicas, $marca->feedback());
        } else
            $request->validate($marca->rules(), $marca->feedback());

        if ($request->file('imagem')) {
            Storage::disk('public')->delete($marca->imagem);
        }

        $imagem = $request->file('imagem');
        $imagem_urn = $imagem->store('imagens', 'public');

        $marca->update(["nome" => $request->input('nome'), "imagem" => $imagem_urn]);

        return response()->json($marca, 200);
    }

    public function destroy($id)
    {
        $marca = $this->marca->find($id);

        if ($marca === null)
            return response()->json(['erro' => 'Impossível realizar a exclusão. O recurso solicitado não existe'], 404);

        Storage::disk('public')->delete($marca->imagem);

        $marca->delete();

        return response()->json(['msg' => 'A marca foi removida com sucesso!'], 200);
    }
}
