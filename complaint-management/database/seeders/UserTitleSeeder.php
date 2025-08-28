<?php

namespace Database\Seeders;

use App\Models\UserTitle;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserTitleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $titles = [
            [
                'title' => 'Newcomer',
                'min_complaints' => 1,
                'max_complaints' => 3,
                'color' => '#10b981',
                'description' => 'New to the platform with 1-3 complaints',
            ],
            [
                'title' => 'Active Contributor',
                'min_complaints' => 4,
                'max_complaints' => 9,
                'color' => '#3b82f6',
                'description' => 'Active user with 4-9 complaints',
            ],
            [
                'title' => 'Veteran Complainer',
                'min_complaints' => 10,
                'max_complaints' => null,
                'color' => '#8b5cf6',
                'description' => 'Experienced user with 10+ complaints',
            ],
        ];

        foreach ($titles as $title) {
            UserTitle::updateOrCreate(
                ['title' => $title['title']],
                $title
            );
        }
    }
}
