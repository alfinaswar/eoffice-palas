<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DownPayment extends Model
{
    use HasFactory, SoftDeletes;
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'down_payments';
    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = ['id'];


    // Relasi ke penerima (User)
    public function getPenerima()
    {
        return $this->hasOne(User::class, 'id', 'Penerima');
    }

    // Relasi ke produk
    public function getProduk()
    {
        return $this->belongsTo(Produk::class, 'IdProduk', 'id');
    }

    // Relasi ke customer (User)
    public function getCustomer()
    {
        return $this->belongsTo(User::class, 'NamaPelanggan', 'id');
    }

    // Relasi ke booking list
    public function getBooking()
    {
        return $this->belongsTo(BookingList::class, 'IdBooking', 'id');
    }

}
