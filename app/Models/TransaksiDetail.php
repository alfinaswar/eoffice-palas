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

    public function transaksi()
    {
        return $this->hasOne(Transaksi::class, 'IdTransaksi', 'id');
    }

    public function getCustomer()
    {
        return $this->hasOne(User::class, 'id', 'IdPelanggan');
    }
}
