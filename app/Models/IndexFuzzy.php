<?php

namespace App\Models;

use App\Models\KalkulatorFuzzy;
use App\Models\RuleFuzzy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class IndexFuzzy extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = [];
    protected $casts = [
        'interval' => 'array',
    ];

    public function ruleFuzzies()
    {
        return $this->hasMany(RuleFuzzy::class, "index_fuzzy_id", "id");
    }

    public function kalkulatorFuzzies()
    {
        return $this->hasMany(KalkulatorFuzzy::class, "index_fuzzy_id", "id");
    }
}
