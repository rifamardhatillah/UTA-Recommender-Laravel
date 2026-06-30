<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Model;

class Alternatif extends Model
{
    use HasFactory;

    /**
     * Kolom yang boleh diisi massal
     *
     * @var array
     */
    protected $fillable = [
        'nama',
        'terdaftar',
    ];

    public function Nilais(): HasMany
        {
            return $this->hasMany(Nilai::class);
        }
}