<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreProjetoRequest;
use App\Http\Requests\UpdateProjetoRequest;
use App\Models\Projeto;
use Illuminate\Http\Request;

class ProjetoController extends Controller
{
    public function index(Request $request)
    {
        $q = $request->query('q');

        $projetos = Projeto::with('cliente')
            ->when($q, function ($query) use ($q) {
                $query->where('nome', 'like', "%{$q}%")
                      ->orWhereHas('cliente', function ($c) use ($q) {
                          $c->where('nome', 'like', "%{$q}%")
                            ->orWhere('email', 'like', "%{$q}%");
                      });
            })
            ->orderBy('id', 'desc')
            ->get();

        return response()->json($projetos);
    }

    public function store(StoreProjetoRequest $request)
    {
        $data = $request->validated();

        $projeto = Projeto::create($data);
        $projeto->load('cliente');

        return response()->json($projeto, 201);
    }

    public function show(Projeto $projeto)
    {
        $projeto->load('cliente');
        return response()->json($projeto);
    }

    public function update(UpdateProjetoRequest $request, Projeto $projeto)
    {
        $data = $request->validated();

        $projeto->update($data);
        $projeto->load('cliente');

        return response()->json($projeto);
    }

    public function destroy(Projeto $projeto)
    {
        $projeto->delete();
        return response()->json(null, 204);
    }
}
