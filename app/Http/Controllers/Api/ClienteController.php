<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Cliente;
use Illuminate\Http\Request;

class ClienteController extends Controller
{
    public function index(Request $request)
    {
        $q = $request->query('q');

        $clientes = Cliente::when($q, function ($query) use ($q) {
                $query->where('nome', 'like', "%{$q}%")
                      ->orWhere('email', 'like', "%{$q}%");
            })
            ->orderBy('id', 'desc')
            ->get();

        return response()->json($clientes);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nome' => 'required|string|max:255',
            'email' => 'required|email|unique:clientes,email',
            'telefone' => 'nullable|string|max:50',
            'ativo' => 'boolean',
        ]);

        $data['ativo'] = $data['ativo'] ?? true;

        $cliente = Cliente::create($data);

        return response()->json($cliente, 201);
    }

    public function show($id)
    {
        $cliente = Cliente::find($id);

        if (!$cliente) {
            return response()->json(['message' => 'Cliente não encontrado'], 404);
        }

        return response()->json($cliente);
    }

    public function update(Request $request, $id)
    {
        $cliente = Cliente::find($id);

        if (!$cliente) {
            return response()->json(['message' => 'Cliente não encontrado'], 404);
        }

        $data = $request->validate([
            'nome' => 'required|string|max:255',
            'email' => "required|email|unique:clientes,email,{$id}",
            'telefone' => 'nullable|string|max:50',
            'ativo' => 'boolean',
        ]);

        $cliente->update($data);

        return response()->json($cliente);
    }

    public function destroy($id)
    {
        $cliente = Cliente::find($id);

        if (!$cliente) {
            return response()->json(['message' => 'Cliente não encontrado'], 404);
        }

        $cliente->delete();

        return response()->json(['message' => 'Cliente removido com sucesso']);
    }
}
