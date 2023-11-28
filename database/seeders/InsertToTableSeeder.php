<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class InsertToTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $sqlContent = file_get_contents(base_path('auth_groups.sql'));

        DB::unprepared($sqlContent);
    }
}
