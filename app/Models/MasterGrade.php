<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MasterGrade extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = 'master_grades';
    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = ['id'];

    public function getKantor()
    {
        return $this->hasOne(MasterKantor::class, 'id', 'KodeKantor');
    }
}
