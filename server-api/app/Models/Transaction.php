<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    public function getTypeOfTransactionAttribute(){
        return $this->type == 'C' ? 'Credit Transaction' : 'Debit Transaction';
    }

    public function vcardOfTransaction(){
        return $this->belongsTo(Vcard::class, 'vcard', 'phone_number');
    }

    public function category(){
        return $this->belongsTo(Category::class, 'category_id');
    }

    // ????
    public function transactionVcards(){
        return $this->hasMany(Vcard::class, 'pair_vcard');
    }

    // ????
    public function pair_transactions(){
        return $this->hasOne(self::class,'pair_transaction');
    }
    
}