<?php

namespace Database\Seeders;
use App\Models\Priority;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PrioritySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create entry for 'New'
        Priority::create([
        'name' => 'High',
        'color' => '#e71313'
    ]);

    // Create entry for 'Closed'
    Priority::create([
        'name' => 'Normal',
        'color' => '#000000'
    ]);

    // Create entry for 'Reopen'
    Priority::create([
        'name' => 'Low',
        'color' => '#1c28ce'
    ]);
    Priority::create([
        'name' => 'Medium',
        'color' => '#1136ee'
    ]);
    }
}
