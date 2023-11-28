<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Purchase extends Model
{
    use HasFactory;
    protected $guarded = [];
    
    public function items()
    {
        return $this->hasMany(Item::class);
    }

    public function transaction()
    {
        return $this->hasOne(Transaction::class);
    }
}
