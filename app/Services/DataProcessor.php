<?php
// app/Services/DataProcessor.php

namespace App\Services;

use Carbon\Carbon;
use App\Models\OfficeIp;

//dataprocessing service

class DataProcessor
{
    public function calculateUserTotalTime($data)
    {
        $userTotalTime = [];

        $officeIps = OfficeIp::all();

        foreach ($data as $item) 
        {
            $userId = $item['user_id'];
            $checkInTime = Carbon::parse($item['checked_in_at']);
            $checkOutTime = Carbon::parse($item['checked_out_at']);
            $userIpAddress = $item['ip_address'];

            // If the user's total time hasn't been initialized, initialize it.
            if (!isset($userTotalTime[$userId])) 
            {
                $userTotalTime[$userId] = [
                'office_hours' => 0,
                'remote_hours' => 0,
                ];
            }


            // Check if the user's IP address matches any office IP in the table
            foreach($officeIps as $office_ips)
            {
                $timeDifferenceHours = $checkOutTime->diffInHours($checkInTime);
                $officeName='unknown Office';
                if($office_ips['office_ip']===$userIpAddress)
                {
                    $officeName=$office_ips['office_name'];
                    
                    $userTotalTime[$userId]['office_hours'] += $timeDifferenceHours;
                }
                else
                {
                    $userTotalTime[$userId]['remote_hours'] += $timeDifferenceHours;
                    
                }
                // $officeNameWithNewline = $officeName . PHP_EOL;
                // echo $officeNameWithNewline;
            }
        }
        return $userTotalTime;
    }
}