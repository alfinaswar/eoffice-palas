<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Transaksi extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'transaksi';

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = ['id'];

    /**
     * Get all of the comments for the Transaksi
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function getTransaksi()
    {
        return $this->hasMany(TransaksiDetail::class, 'IdTransaksi', 'id');
    }

    /**
     * Get the user associated with the Transaksi
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function getUser()
    {
        return $this->hasOne(User::class, 'id', 'IdPelanggan');
    }
    public function getCustomer()
    {
        return $this->hasOne(User::class, 'id', 'IdPelanggan');
    }

    public function getProduk()
    {
        return $this->hasOne(Produk::class, 'id', 'IdProduk');
    }

    public function getDurasiPembayaran()
    {
        return $this->hasOne(MasterAngsuran::class, 'id', 'DurasiPembayaran');
    }
    public function getBooking()
    {
        return $this->hasOne(BookingList::class, 'id', 'IdBooking');
    }
    public function getDownPayment()
    {
        return $this->hasOne(DownPayment::class, 'id', 'IdBooking');
    }

}
