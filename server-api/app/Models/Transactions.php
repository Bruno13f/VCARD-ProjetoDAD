<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transactions extends Model
{
    use HasFactory;

    public function getTypeOfTransactionAttribute(){
        return $this->type == 'C' ? 'Credit Transaction' : 'Debit Transaction';
    }

    public function vcards(){
        return $this->belongsTo(Vcards::class, 'vcards');
    }

    public function categories(){
        return $this->belongsTo(Categories::class, 'category_id');
    }

    // ????
    public function transactionVcards(){
        return $this->hasMany(Transactions::class, 'pair_vcard');
    }

    // ????
    public function pair_transactions(){
        return $this->hasOne(self::class,'pair_transaction');
    }
    
}
