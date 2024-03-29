<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Status;


class StatusSeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
{
    // Create entry for 'New'
    Status::create([
        'name' => 'New',
        'color' => '#ce3636'
    ]);

    // Create entry for 'Closed'
    Status::create([
        'name' => 'Closed',
        'color' => '#6a4d4d'
    ]);

    // Create entry for 'Reopen'
    Status::create([
        'name' => 'Reopen',
        'color' => '#e8a617'
    ]);
}
}
