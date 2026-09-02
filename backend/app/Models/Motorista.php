<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Motorista extends Model
{
    use HasFactory;

    protected $fillable = ['nome'];

    public function coletas(): HasMany
    {
        return $this->hasMany(Coleta::class);
    }
}
