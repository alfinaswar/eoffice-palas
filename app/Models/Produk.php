<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Produk extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = 'produks';
    protected $guarded = ['id'];

    public function getGrade()
    {
        return $this->belongsTo(MasterGrade::class, 'Grade', 'id');
    }
    public function getJenis()
    {
        return $this->belongsTo(MasterJenis::class, 'Jenis', 'id');
    }
    public function getProyek()
    {
        return $this->belongsTo(MasterProjek::class, 'Proyek', 'id');
    }
    public function getDataBooking()
    {
        return $this->hasOne(BookingList::class, 'IdProduk', 'id');
    }
}
