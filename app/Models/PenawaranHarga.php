<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PenawaranHarga extends Model
{
    use HasFactory, SoftDeletes;
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'penawaran_hargas';
    protected $guarded = ['id'];

    public function DetailPenawaran()
    {
        return $this->hasMany(PenawaranHargaDetail::class, 'IdPenawaran', 'id');
    }
}
