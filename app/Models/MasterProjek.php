<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MasterProjek extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = 'master_projeks';
    protected $guarded = ['id'];

    public function getKantor()
    {
        return $this->hasOne(MasterKantor::class, 'id', 'KodeKantor');
    }
}
