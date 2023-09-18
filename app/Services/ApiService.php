<?php
// app/Services/ApiService.php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class ApiService
{

    //data fetch service
    public function fetchApiData($url)
    {
        try 
        {
            return Http::get($url);
        } 
        catch (\Exception $e) 
        {
            throw new \Exception('API request failed: ' . $e->getMessage());
        }
    }
}