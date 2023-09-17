<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http; 
use Carbon\Carbon;
use App\Models\TotalTime;
use App\Models\OfficeIp;
use Illuminate\Support\Facades\DB;
use App\Models\data; 

class Timecalculation extends Command
{
    protected $signature = 'Timecalculation';

    protected $description = 'Description of your custom command';

    public function handle()
    {
        
        $url = 'https://backend.grabdata.org/api/pf'; //API endpoint

        try {
            $response = Http::get($url);

            if ($response->successful()) {
                $data = $response->json(); //API response in JSON
                $data=$data['data'];
                // Initialize an array to store the total time in hours for each user
                $userTotalTime = [];
                // Fetch office IP data from the database
                $officeIps = OfficeIp::all();
                $index=0;
                foreach ($data as $item) 
                {
                    $userId = $item['user_id'];
                    $checkInTime = Carbon::parse($item['checked_in_at']);
                    $checkOutTime = Carbon::parse($item['checked_out_at']);
                    $userIpAddress = $item['ip_address'];


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
                    $index++;
                }
                //Store the total time in the database
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
                $this->info('Time calculation and storage completed successfully.');
            } 
            else 
            {
                $this->error('API request failed: ' . $response->status());
            }
        } 
        catch (\Exception $e) 
        {
            $this->error('API request failed: ' . $e->getMessage());
        }
    }
}