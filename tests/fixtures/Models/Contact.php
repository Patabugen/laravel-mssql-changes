<?php

namespace Patabugen\MssqlChanges\Tests\fixtures\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property string $Firstname
 * @property string $Surname
 */
class Contact extends Model
{
    use HasFactory;

    public $timestamps = false;

    public $primaryKey = 'ContactID';

    public $table = 'Contacts';

    public $guarded = [];
}
