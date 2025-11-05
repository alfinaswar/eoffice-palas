<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransaksiDetail extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'transaksi_details';

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = ['id'];

    /**
     * Get the user associated with the TransaksiDetail
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function transaksi()
    {
        return $this->belongsTo(Transaksi::class, 'IdTransaksi', 'id');
    }

    /**
     * Get the user associated with the TransaksiDetail
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function getCustomer()
    {
        return $this->hasOne(User::class, 'id', 'IdPelanggan');
    }
}
