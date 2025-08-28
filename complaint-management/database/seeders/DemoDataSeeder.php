<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Complaint;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DemoDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create demo users
        $user1 = User::create([
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'password' => Hash::make('password'),
            'title' => 'Newcomer',
        ]);

        $user2 = User::create([
            'name' => 'Jane Smith',
            'email' => 'jane@example.com',
            'password' => Hash::make('password'),
            'title' => 'Active Contributor',
        ]);

        $user3 = User::create([
            'name' => 'Bob Wilson',
            'email' => 'bob@example.com',
            'password' => Hash::make('password'),
            'title' => 'Veteran Complainer',
        ]);

        // Create sample complaints
        $complaints = [
            [
                'title' => 'Poor Customer Service Experience',
                'description' => 'I had a terrible experience with your customer service team. They were unhelpful and rude when I called to inquire about my order status. The representative seemed disinterested and provided incorrect information.',
                'category' => 'Service',
                'priority' => 'High',
                'status' => 'Pending',
                'user_id' => $user1->id,
            ],
            [
                'title' => 'Defective Product Received',
                'description' => 'The product I ordered arrived damaged. The packaging was intact, but the item inside was clearly defective. I need a replacement or refund as soon as possible.',
                'category' => 'Product',
                'priority' => 'Medium',
                'status' => 'In Progress',
                'user_id' => $user1->id,
                'assigned_to' => 'Quality Assurance Team',
            ],
            [
                'title' => 'Late Delivery - Time Sensitive Order',
                'description' => 'My order was supposed to arrive within 2 business days, but it\'s been a week and I still haven\'t received it. This was a time-sensitive purchase for a birthday gift.',
                'category' => 'Delivery',
                'priority' => 'High',
                'status' => 'Resolved',
                'user_id' => $user2->id,
                'admin_notes' => 'Investigated with shipping partner. Package was misrouted but has been located and delivered. Customer compensated with express shipping credit.',
            ],
            [
                'title' => 'Incorrect Billing Amount',
                'description' => 'I was charged twice for the same order. The first charge was correct, but there\'s an additional charge on my credit card that I don\'t understand.',
                'category' => 'Billing',
                'priority' => 'Medium',
                'status' => 'In Progress',
                'user_id' => $user2->id,
                'assigned_to' => 'Billing Department',
            ],
            [
                'title' => 'Website Login Issues',
                'description' => 'I\'ve been unable to log into my account for the past three days. I\'ve tried resetting my password multiple times, but I never receive the reset email.',
                'category' => 'Support',
                'priority' => 'Medium',
                'status' => 'Pending',
                'user_id' => $user2->id,
            ],
            [
                'title' => 'Missing Items from Order',
                'description' => 'I ordered 5 items but only received 3. The packing slip shows all 5 items, but 2 are missing from the package. Order number: #12345.',
                'category' => 'Product',
                'priority' => 'High',
                'status' => 'Resolved',
                'user_id' => $user2->id,
                'admin_notes' => 'Missing items shipped separately due to inventory split. Customer notified and tracking information provided.',
            ],
            [
                'title' => 'App Crashes on Startup',
                'description' => 'The mobile app crashes immediately when I try to open it. I\'ve tried uninstalling and reinstalling, but the problem persists. Using iPhone 12 with iOS 15.',
                'category' => 'Support',
                'priority' => 'Medium',
                'status' => 'In Progress',
                'user_id' => $user3->id,
                'assigned_to' => 'Technical Support',
            ],
            [
                'title' => 'Promotional Code Not Working',
                'description' => 'I have a promotional code that should give me 20% off, but it keeps saying "invalid code" at checkout. The code hasn\'t expired and this is my first time using it.',
                'category' => 'Other',
                'priority' => 'Low',
                'status' => 'Resolved',
                'user_id' => $user3->id,
                'admin_notes' => 'Code was case-sensitive. Customer assisted over chat and order completed successfully.',
            ],
            // Guest complaints
            [
                'title' => 'Food Quality Concern',
                'description' => 'The food I ordered was cold when it arrived and didn\'t taste fresh. This is not the quality I expected based on previous orders.',
                'category' => 'Service',
                'priority' => 'Medium',
                'status' => 'Pending',
                'guest_name' => 'Sarah Johnson',
                'guest_email' => 'sarah.johnson@email.com',
            ],
            [
                'title' => 'Refund Request - Cancelled Event',
                'description' => 'I purchased tickets for an event that was cancelled due to weather. I was told I would receive a full refund, but it\'s been two weeks and I haven\'t seen the money in my account.',
                'category' => 'Billing',
                'priority' => 'High',
                'status' => 'In Progress',
                'guest_name' => 'Mike Chen',
                'guest_email' => 'mike.chen@email.com',
                'assigned_to' => 'Finance Team',
            ],
        ];

        foreach ($complaints as $complaintData) {
            Complaint::create($complaintData);
        }

        // Update user titles based on complaint count
        foreach ([$user1, $user2, $user3] as $user) {
            $user->updateTitle();
        }
    }
}