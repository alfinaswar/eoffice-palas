<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BookingList extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'booking_lists';

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = ['id'];

    public function getCustomer()
    {
        return $this->hasOne(User::class, 'id', 'NamaPelanggan');
    }
    public function getKaryawan()
    {
        return $this->hasOne(User::class, 'id', 'Penerima');
    }
    public function getProduk()
    {
        return $this->hasOne(Produk::class, 'id', 'IdProduk');
    }
    public function getPenawaran()
    {
        return $this->hasOne(PenawaranHarga::class, 'id', 'IdPenawaran');
    }

}
