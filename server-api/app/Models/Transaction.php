<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Transaction extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = ['type','description'];

    public function getTypeOfTransactionAttribute(){
        return $this->type == 'C' ? 'Credit Transaction' : 'Debit Transaction';
    }

    public function vcardOfTransaction(){
        return $this->belongsTo(Vcard::class, 'vcard', 'phone_number')->withTrashed();
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
