<?php

// app/Services/StorageService.php

namespace App\Services;

use App\Models\TotalTime;

//database service

class StorageService
{
    public function storeTotalTime($userTotalTime)
    {
        foreach ($userTotalTime as $userId => $totalTimeHours) 
        {
            $officeHours = $totalTimeHours['office_hours'];
            $remoteHours = $totalTimeHours['remote_hours'];
        
            // Calculate the total hours
            $totalHours = $officeHours + $remoteHours;
        
            // Determine the status based on office hours
            if ($officeHours > 5) {
                $status = "Present";
            } elseif ($officeHours >= 3 && $officeHours <= 5) {
                $status = "Half Day";
            } else {
                $status = "Absent";
            }
        
            // Create a TotalTime record
            TotalTime::create([
                'user_id' => $userId,
                'office_hours' => $officeHours,
                'remote_hours' => $remoteHours,
                'total_time_hours' => $totalHours,
                'status' => $status,
            ]);
            
        }
    }
}