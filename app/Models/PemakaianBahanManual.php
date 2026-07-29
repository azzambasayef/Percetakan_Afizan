<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PemakaianBahanManual extends Model
{
    protected $table = 'pemakaian_bahan_manual';
    protected $primaryKey = 'id_pemakaian';
    protected $guarded = [];

    public function bahan() { return $this->belongsTo(BahanBaku::class, 'id_bahan'); }
    public function user() { return $this->belongsTo(User::class, 'id_user'); }
}
