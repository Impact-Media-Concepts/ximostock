<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Property extends Model
{
    use HasFactory, SoftDeletes;
    
    //Values begint met een type wat: multiselect, singleselect, number, text of bool kan zijn
    //als het type multi- of singelselect is dan is er ook een options velt waar de mogenlijkheden in staan

    protected $guarded = ['id'];
    protected $touches = ['product'];


    public function products(){
        return $this->belongsToMany(Product::class)
        ->using(ProductProperty::class)
        ->withPivot('property_value');
    }

    public function getTypeAttribute(){
        $json = json_decode($this->values);
        return $json->type;
    }

    public function getOptionsAttribute(){
        $json = json_decode($this->values);
        return $json->options;
    }
}