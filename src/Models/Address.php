<?php

namespace Patabugen\MssqlChanges\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property string $first_line
 * @property string $second_line
 */
class Address extends Model
{
    use HasFactory;

    protected $primaryKey = 'AddressID';

    public $timestamps = false;

    public $table = 'Addresses';

    public $guarded = [];
}
