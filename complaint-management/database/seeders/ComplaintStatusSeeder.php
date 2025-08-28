<?php

namespace Database\Seeders;

use App\Models\ComplaintStatus;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ComplaintStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $statuses = [
            [
                'name' => 'Pending',
                'color' => '#f59e0b',
                'description' => 'Complaint is waiting to be reviewed',
            ],
            [
                'name' => 'In Progress',
                'color' => '#3b82f6',
                'description' => 'Complaint is being actively worked on',
            ],
            [
                'name' => 'Resolved',
                'color' => '#10b981',
                'description' => 'Complaint has been resolved',
            ],
        ];

        foreach ($statuses as $status) {
            ComplaintStatus::updateOrCreate(
                ['name' => $status['name']],
                $status
            );
        }
    }
}
