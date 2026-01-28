<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Cliente;
use App\Models\Lancamento;

class Projeto extends Model
{
    protected $table = 'projetos';

    protected $fillable = [
        'cliente_id',
        'nome',
        'descricao',
        'data_inicio',
        'data_fim',
        'valor_contrato',
        'custo_hora_base',
        'status',
    ];

    // Projeto pertence a um cliente
    public function cliente()
    {
        return $this->belongsTo(Cliente::class);
    }

    // Projeto tem vários lançamentos (timesheet)
    public function lancamentos()
    {
        return $this->hasMany(Lancamento::class);
    }
}
