<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Kriteria extends Model
{
    use HasFactory;

    /**
     * fillable
     *
     * @var array
     */
    protected $fillable = [
        'nama_kriteria',
        'tipe',
        'bobot',
    ];

    public function Nilais(): HasMany
    {
        return $this->hasMany(Nilai::class);
    }
}
