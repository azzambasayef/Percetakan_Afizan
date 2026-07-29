<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    protected $primaryKey = 'id_user';
    protected $guarded = [];

    public function pesanan() { return $this->hasMany(Pesanan::class, 'id_user'); }
    public function pemakaianManual() { return $this->hasMany(PemakaianBahanManual::class, 'id_user'); }
    public function pembelianBahan() { return $this->hasMany(PembelianBahan::class, 'id_user'); }
}
