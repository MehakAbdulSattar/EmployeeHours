<?php


// app/Console/Commands/Timecalculation.php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\ApiService;
use App\Services\DataProcessor;
use App\Services\StorageService;

class Timecalculation extends Command
{
    // ...

    protected $signature = 'Timecalculation';
    protected $description = 'Description of your custom command';
    public function __construct(ApiService $apiService, DataProcessor $dataProcessor, StorageService $storageService)
    {
        parent::__construct();

        $this->apiService = $apiService;
        $this->dataProcessor = $dataProcessor;
        $this->storageService = $storageService;
    }


    public function handle()
    {
        $url = 'https://backend.grabdata.org/api/pf'; // API endpoint

        // Use the ApiService to fetch data
         $response = $this->apiService->fetchApiData($url);

        // Check if the API request was successful
        if ($response->successful()) 
        {
            $data = $response->json(); // API response in JSON

            // Extract the 'data' key from the JSON response.
            $data = $data['data'];

            // Calculate user total time using a data processing service.
            $userTotalTime = $this->dataProcessor->calculateUserTotalTime($data);

            // Store the calculated user total time in a database using a storage service.
            $this->storageService->storeTotalTime($userTotalTime);

            $this->info('Time calculation and storage completed successfully.');
        } 
        else 
        {
            $this->error('API request failed: ' . $response->status());
        }
    }
}

