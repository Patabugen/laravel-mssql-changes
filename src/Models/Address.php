<?php

namespace Patabugen\MssqlChanges\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Address extends Model
{
    use HasFactory;

    protected $primaryKey = 'AddressID';

    public $timestamps = false;

    public $table = 'Addresses';

    public $guarded = [];
}
