<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class data extends Model
{
    use HasFactory;

    protected $fillable = ['user_id','ip_address', 'checked_in_at', 'checked_out_at'];
}
