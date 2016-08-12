<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Apps extends Model {

    protected $table = 'apps';
    protected $fillable = ['name', 'hash'];

}
