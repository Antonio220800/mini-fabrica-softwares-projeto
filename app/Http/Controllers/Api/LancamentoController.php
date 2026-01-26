<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Lancamento;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class LancamentoController extends Controller
{
    public function index(Request $request)
    {
        $query = Lancamento::query();

        if ($request->projeto_id) {
            $query->where('projeto_id', $request->projeto_id);
        }

        if ($request->inicio && $request->fim) {
            $query->whereBetween('data', [$request->inicio, $request->fim]);
        }

        return response()->json(
            $query->orderBy('data', 'desc')->get()
        );
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'projeto_id'  => ['required', 'exists:projetos,id'],
            'colaborador' => ['required', 'string', 'max:255'],
            'data'        => ['required', 'date'],
            'horas'       => ['required', 'numeric', 'gt:0'],
            'tipo'        => ['required', Rule::in(['corretiva', 'evolutiva', 'implantacao', 'legislativa'])],
            'descricao'   => ['nullable', 'string'],
        ]);

        $lancamento = Lancamento::create($data);

        return response()->json($lancamento, 201);
    }

    public function show(Lancamento $lancamento)
    {
        return response()->json($lancamento);
    }

    public function update(Request $request, Lancamento $lancamento)
    {
        $data = $request->validate([
            'projeto_id'  => ['sometimes', 'exists:projetos,id'],
            'colaborador' => ['sometimes', 'string', 'max:255'],
            'data'        => ['sometimes', 'date'],
            'horas'       => ['sometimes', 'numeric', 'gt:0'],
            'tipo'        => ['sometimes', Rule::in(['corretiva', 'evolutiva', 'implantacao', 'legislativa'])],
            'descricao'   => ['nullable', 'string'],
        ]);

        $lancamento->update($data);

        return response()->json($lancamento);
    }

    public function destroy(Lancamento $lancamento)
    {
        $lancamento->delete();
        return response()->json(null, 204);
    }
}
