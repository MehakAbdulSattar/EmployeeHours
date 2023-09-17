<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class TotalTime extends Model
{
    use HasFactory;

    protected $table = 'total_times'; // table name, it's different from the model name

    protected $fillable = [
        'user_id',
        'office_hours',
        'remote_hours',
        'total_time_hours',
        'status',
    ];
}

