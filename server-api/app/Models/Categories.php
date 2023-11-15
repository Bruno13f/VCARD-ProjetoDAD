<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Categories extends Model
{
    use HasFactory;

    public function getTypeOfCategoryAttribute(){    
        return $this->type == 'C' ? 'Credit' : 'Debit';
    }
    
    public function vcards(){
        return $this->belongsTo(Vcards::class, 'vcards');
    }

    public function transactions(){
        return $this->hasMany(Transactions::class, 'category_id');
    }
    
}
