<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreProjetoRequest;
use App\Http\Requests\UpdateProjetoRequest;
use App\Models\Projeto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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
        $projeto = Projeto::create($request->validated());
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
        $projeto->update($request->validated());
        $projeto->load('cliente');

        return response()->json($projeto);
    }

    public function destroy(Projeto $projeto)
    {
        $projeto->delete();
        return response()->json(null, 204);
    }

    /**
     * DASHBOARD DE LUCRATIVIDADE
     * GET /api/projetos/{id}/dashboard?inicio=YYYY-MM-DD&fim=YYYY-MM-DD
     */
    public function dashboard(Request $request, Projeto $projeto)
    {
        $inicio = $request->query('inicio');
        $fim    = $request->query('fim');

        $query = $projeto->lancamentos();

        if ($inicio && $fim) {
            $query->whereBetween('data', [$inicio, $fim]);
        }

        $horasTotais = (float) $query->sum('horas');
        $custoTotal  = $horasTotais * $projeto->custo_hora_base;
        $receita     = (float) $projeto->valor_contrato;
        $margem      = $receita - $custoTotal;
        $margemPct   = $receita > 0 ? ($margem / $receita) * 100 : 0;
        $breakEven   = $projeto->custo_hora_base > 0
            ? $receita / $projeto->custo_hora_base
            : 0;

        $resumoPorTipo = $query
            ->select(
                'tipo',
                DB::raw('SUM(horas) as horas'),
                DB::raw('SUM(horas) * ' . $projeto->custo_hora_base . ' as custo')
            )
            ->groupBy('tipo')
            ->get();

        return response()->json([
            'projeto' => [
                'id' => $projeto->id,
                'nome' => $projeto->nome,
                'cliente' => $projeto->cliente?->nome,
            ],
            'periodo' => [
                'inicio' => $inicio,
                'fim' => $fim,
            ],
            'indicadores' => [
                'horas_totais' => $horasTotais,
                'custo_total' => round($custoTotal, 2),
                'receita' => round($receita, 2),
                'margem_bruta' => round($margem, 2),
                'margem_percentual' => round($margemPct, 2),
                'break_even_horas' => round($breakEven, 2),
            ],
            'resumo_por_tipo' => $resumoPorTipo,
        ]);
    }
}
