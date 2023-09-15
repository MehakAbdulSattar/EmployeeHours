<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class TotalTime extends Model
{
    use HasFactory;

    protected $table = 'total_times'; // Specify the table name if it's different from the model name

    protected $fillable = [
        'user_id',
        'total_time_hours',
    ];
}

