<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Lancamento extends Model
{
    protected $table = 'lancamentos';

    protected $fillable = [
        'projeto_id',
        'colaborador',
        'data',
        'horas',
        'tipo',
        'descricao',
    ];

    public function projeto()
    {
        return $this->belongsTo(Projeto::class);
    }
}
