<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Projeto;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

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

    public function store(Request $request)
    {
        $data = $request->validate([
            'cliente_id'      => ['required', 'integer', 'exists:clientes,id'],
            'nome'            => ['required', 'string', 'max:255'],
            'descricao'       => ['nullable', 'string'],
            'data_inicio'     => ['required', 'date'],
            'data_fim'        => ['nullable', 'date', 'after_or_equal:data_inicio'],
            'valor_contrato'  => ['required', 'numeric', 'gt:0'],
            'custo_hora_base' => ['required', 'numeric', 'gt:0'],
            'status'          => ['required', 'string', Rule::in(['planejado', 'em_andamento', 'pausado', 'finalizado'])],
        ]);

        $projeto = Projeto::create($data);
        $projeto->load('cliente');

        return response()->json($projeto, 201);
    }

    public function show(Projeto $projeto)
    {
        $projeto->load('cliente');
        return response()->json($projeto);
    }

    public function update(Request $request, Projeto $projeto)
    {
        $data = $request->validate([
            'cliente_id'      => ['sometimes', 'required', 'integer', 'exists:clientes,id'],
            'nome'            => ['sometimes', 'required', 'string', 'max:255'],
            'descricao'       => ['sometimes', 'nullable', 'string'],
            'data_inicio'     => ['sometimes', 'required', 'date'],
            'data_fim'        => ['sometimes', 'nullable', 'date'],
            'valor_contrato'  => ['sometimes', 'required', 'numeric', 'gt:0'],
            'custo_hora_base' => ['sometimes', 'required', 'numeric', 'gt:0'],
            'status'          => ['sometimes', 'required', 'string', Rule::in(['planejado', 'em_andamento', 'pausado', 'finalizado'])],
        ]);

        // Se data_inicio/data_fim vierem, valida coerência (fim >= inicio)
        $inicio = $data['data_inicio'] ?? $projeto->data_inicio;
        $fim    = array_key_exists('data_fim', $data) ? $data['data_fim'] : $projeto->data_fim;

        if ($fim && $inicio && $fim < $inicio) {
            return response()->json([
                'message' => 'A data_fim deve ser maior ou igual à data_inicio.'
            ], 422);
        }

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
