<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vcards extends Model
{
    use HasFactory;

    protected $primaryKey = 'phone_number';

    public function transactions(){
        return $this->hasMany(Transactions::class, 'vcard');
    }

    // ????
    public function vcardPair(){
        return $this->hasMany(Transactions::class, 'pair_vcard');
    }

    public function categories(){
        return $this->hasMany(Categories::class, 'vcard');
    } 



}
