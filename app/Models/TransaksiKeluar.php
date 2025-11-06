<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TransaksiKeluar extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'transaksi_keluars';

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = ['id'];

    /**
     * Get the user associated with the TransaksiKeluar
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function getPetugas()
    {
        return $this->hasOne(User::class, 'id', 'IdPetugas');
    }

    public function getJenis()
    {
        return $this->hasOne(MasterJenisPengeluaran::class, 'id', 'Jenis');
    }

    public function getKantor()
    {
        return $this->hasOne(MasterKantor::class, 'id', 'KodeKantor');
    }

    public function getDetail()
    {
        return $this->hasMany(TransaksiKeluarDetail::class, 'IdTransaksiKeluar', 'id');
    }
}
