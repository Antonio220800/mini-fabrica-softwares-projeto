<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Cliente; // 👈 ADICIONA ISSO

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

    public function cliente()
    {
        return $this->belongsTo(Cliente::class);
    }
}
