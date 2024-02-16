<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Property extends Model
{
    use HasFactory;
    
    //Values begint met een type wat: multiselect, singleselect, number, text of bool kan zijn
    //als het type multi- of singelselect is dan is er ook een options velt waar de mogenlijkheden in staan
}
