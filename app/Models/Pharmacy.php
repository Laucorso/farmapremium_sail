<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pharmacy extends Model
{
    use HasFactory;
    protected $guarded=[];

    public function transactions() //otorgados
    {
        return $this->hasMany(Transaction::class, 'pharmacy_id');
    }

    public function canjeados()
    {
        return $this->hasMany(Transaction::class, 'pharmacy_id')
                    ->where('type', 'canjeados')
                    ->sum('points');
    }

}
