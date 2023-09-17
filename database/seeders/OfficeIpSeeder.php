<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
class OfficeIpSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('office_ips')->insert([
            ['office_ip' => '192.168.1.100', 'office_name' => 'Office A'],
            ['office_ip' => '10.0.0.1', 'office_name' => 'Office B'],
            ['office_ip' => '172.16.0.10', 'office_name' => 'Office C'],
            ['office_ip' => '192.168.2.50', 'office_name' => 'Office D'],
            ['office_ip' => '10.1.1.100', 'office_name' => 'Office E'],
            ['office_ip' => '172.17.0.5', 'office_name' => 'Office F'],
            ['office_ip' => '192.168.3.25', 'office_name' => 'Office G'],
            ['office_ip' => '10.2.2.200', 'office_name' => 'Office H'],
            ['office_ip' => '172.18.0.15', 'office_name' => 'Office I'],
            ['office_ip' => '192.168.4.75', 'office_name' => 'Office J'],


           


        ]);
    }
}
