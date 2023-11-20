<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Vcard extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $primaryKey = 'phone_number';
    protected $fillable = ['phone_number', 'name', 'email', 'photo_url', 'blocked', 'max_debit', 'custom_options', 'custom_data'];
    
    public function transactions(){
        return $this->hasMany(Transaction::class, 'vcard', 'phone_number');
    }

    // ????
    public function vcardPair(){
        return $this->hasMany(Transaction::class, 'pair_vcard');
    }

    public function categories(){
        return $this->hasMany(Category::class, 'vcard');
    }

}
