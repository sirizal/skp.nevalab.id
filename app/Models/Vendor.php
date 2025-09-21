<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Vendor extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'address1',
        'address2',
        'address3',
        'village',
        'district',
        'sub_district',
        'province',
        'postal_code',
        'contact_person',
        'email',
        'contact_no',
    ];
}
