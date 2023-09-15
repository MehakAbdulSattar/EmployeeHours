<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http; 
use Carbon\Carbon;
use App\Models\TotalTime; // Import the TotalTime model
use App\Models\data; 

class Timecalculation extends Command
{
    protected $signature = 'Timecalculation';

    protected $description = 'Description of your custom command';

    public function handle()
    {
        $url = 'http://10.0.10.214:9090/api/worktime/get'; // Replace with your API endpoint

        try {
            $response = Http::get($url);

            if ($response->successful()) {
                $data = $response->json(); // Assuming the API response is in JSON format

                // Initialize an array to store the total time in hours for each user
                $userTotalTime = [];

                foreach ($data as $item) {
                    $userId = $item['user_id'];
                    $checkInTime = Carbon::parse($item['checked_in_at']);
                    $checkOutTime = Carbon::parse($item['checked_out_at']);

                    // Calculate the time difference in hours for this user
                    $timeDifferenceHours = $checkOutTime->diffInHours($checkInTime);

                    // Add the time difference to the user's total time
                    if (!isset($userTotalTime[$userId])) {
                        $userTotalTime[$userId] = 0;
                    }
                    $userTotalTime[$userId] += $timeDifferenceHours;
                }

                // Store the total time in the database
                foreach ($userTotalTime as $userId => $totalTimeHours) {
                    TotalTime::create([
                        'user_id' => $userId,
                        'total_time_hours' => $totalTimeHours,
                    ]);
                }

                $this->info('Time calculation and storage completed successfully.');
            } else {
                $this->error('API request failed: ' . $response->status());
            }
        } catch (\Exception $e) {
            $this->error('API request failed: ' . $e->getMessage());
        }
    }
}


// class Timecalculation extends Command
// {
//     protected $signature = 'Timecalculation';

//     protected $description = 'Description of your custom command';

//     public function handle()
//     {
//         $url = 'http://10.0.10.214:9090/api/worktime/get'; // Replace with your API endpoint

//         try {
//             $response = Http::get($url);

//             if ($response->successful()) {
//                 $data = $response->json(); // Assuming the API response is in JSON format

//                 // Initialize an array to store the total time in hours for each user
//                 $userTotalTime = [];

//                 foreach ($data as $item) {
//                     $userId = $item['user_id'];
//                     $checkInTime = Carbon::parse($item['checked_in_at']);
//                     $checkOutTime = Carbon::parse($item['checked_out_at']);

//                     // Calculate the time difference in hours for this user
//                     $timeDifferenceHours = $checkOutTime->diffInHours($checkInTime);

//                     // Add the time difference to the user's total time
//                     if (!isset($userTotalTime[$userId])) {
//                         $userTotalTime[$userId] = 0;
//                     }
//                     $userTotalTime[$userId] += $timeDifferenceHours;
//                 }

//                 // Output the total time in hours for each user
//                 foreach ($userTotalTime as $userId => $totalTimeHours) {
//                     $this->info("User ID: $userId, Total Time (hours): $totalTimeHours");
//                 }

//                 $this->info('Time calculation completed successfully.');
//             } else {
//                 $this->error('API request failed: ' . $response->status());
//             }
//         } catch (\Exception $e) {
//             $this->error('API request failed: ' . $e->getMessage());
//         }
//     }
// }


