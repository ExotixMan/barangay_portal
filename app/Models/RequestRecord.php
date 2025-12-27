<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RequestRecord extends Model
{
    protected $table = 'requests';

    protected $primaryKey = 'request_id';

    public $timestamps = false;
}
