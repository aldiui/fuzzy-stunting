<?php

namespace App\Models;

use App\Models\IndexFuzzy;
use App\Models\VariabelFuzzy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class RuleFuzzy extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = [];
    public function variabelFuzzyBbu()
    {
        return $this->belongsTo(VariabelFuzzy::class, "variabel_fuzzy_bbu_id", "id");
    }

    public function variabelFuzzyTbu()
    {
        return $this->belongsTo(VariabelFuzzy::class, "variabel_fuzzy_tbu_id", "id");
    }

    public function variabelFuzzyBbtb()
    {
        return $this->belongsTo(VariabelFuzzy::class, "variabel_fuzzy_bbtb_id", "id");
    }

    public function indexFuzzy()
    {
        return $this->belongsTo(IndexFuzzy::class, "index_fuzzy_id", "id");
    }
}
