<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Artikel extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = [];

    public function getGambarUrlAttribute()
    {
        return $this->attributes['gambar'] ? url('storage/' . $this->attributes['gambar']) : null;
    }

    public function getCreatedAtForHumansAttribute()
    {
        return $this->created_at ? $this->created_at->diffForHumans() : null;
    }

}
