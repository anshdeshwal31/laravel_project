<?php

namespace Database\Seeders;

use App\Models\FundingOpportunity;
use App\Models\FundingRequest;
use App\Models\Message;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class ChatDemoSeeder extends Seeder
{
    public function run(): void
    {
        // Get or create startups and investors from the main seeder
        $startups = [
            User::where('email', 'ava.startup@larawell.test')->first() ?? User::create(['email' => 'ava.startup@larawell.test', 'name' => 'Ava Startup', 'password' => Hash::make('password'), 'role' => 'startup', 'is_verified' => true, 'email_verified_at' => now()]),
            User::where('email', 'noah.startup@larawell.test')->first() ?? User::create(['email' => 'noah.startup@larawell.test', 'name' => 'Noah Startup', 'password' => Hash::make('password'), 'role' => 'startup', 'is_verified' => true, 'email_verified_at' => now()]),
            User::where('email', 'zara.startup@larawell.test')->first() ?? User::create(['email' => 'zara.startup@larawell.test', 'name' => 'Zara Startup', 'password' => Hash::make('password'), 'role' => 'startup', 'is_verified' => true, 'email_verified_at' => now()]),
        ];

        $investors = [
            User::where('email', 'maya.investor@larawell.test')->first() ?? User::create(['email' => 'maya.investor@larawell.test', 'name' => 'Maya Investor', 'password' => Hash::make('password'), 'role' => 'investor', 'is_verified' => true, 'email_verified_at' => now()]),
            User::where('email', 'daniel.angel@larawell.test')->first() ?? User::create(['email' => 'daniel.angel@larawell.test', 'name' => 'Daniel Angel', 'password' => Hash::make('password'), 'role' => 'investor', 'is_verified' => true, 'email_verified_at' => now()]),
        ];

        // Create multiple funding opportunities
        $opportunities = [];
        $opportunities[] = FundingOpportunity::firstOrCreate(
            ['title' => 'SaaS Growth Capital Opportunity'],
            [
                'user_id' => $investors[0]->id,
                'description' => 'Seeking Series A SaaS company with strong product-market fit.',
                'industry' => 'SaaS',
                'stage' => 'series-a',
                'min_amount' => 500000,
                'max_amount' => 2000000,
                'location' => 'Remote',
                'is_active' => true,
            ]
        );

        $opportunities[] = FundingOpportunity::firstOrCreate(
            ['title' => 'FinTech Early Stage Fund'],
            [
                'user_id' => $investors[1]->id,
                'description' => 'Investing in innovative FinTech solutions for underbanked communities.',
                'industry' => 'FinTech',
                'stage' => 'seed',
                'min_amount' => 100000,
                'max_amount' => 500000,
                'location' => 'New York',
                'is_active' => true,
            ]
        );

        $opportunities[] = FundingOpportunity::firstOrCreate(
            ['title' => 'AI & ML Innovation Fund'],
            [
                'user_id' => $investors[0]->id,
                'description' => 'Focus on AI startups solving real-world enterprise problems.',
                'industry' => 'AI/ML',
                'stage' => 'seed',
                'min_amount' => 250000,
                'max_amount' => 1500000,
                'location' => 'Remote',
                'is_active' => true,
            ]
        );

        // Create funding requests with different statuses
        // PENDING REQUEST
        $pendingRequest = FundingRequest::firstOrCreate(
            ['startup_user_id' => $startups[0]->id, 'investor_user_id' => $investors[0]->id, 'funding_opportunity_id' => $opportunities[0]->id],
            [
                'requested_amount' => 750000,
                'message' => 'We are seeking $750k to accelerate our GTM and expand the team.',
                'status' => 'pending',
            ]
        );
        Message::create(['funding_request_id' => $pendingRequest->id, 'sender_id' => $startups[0]->id, 'body' => 'Hi Maya, thanks for reviewing our application!']);
        Message::create(['funding_request_id' => $pendingRequest->id, 'sender_id' => $investors[0]->id, 'body' => 'Great pitch deck! Can we schedule a call this week?']);
        Message::create(['funding_request_id' => $pendingRequest->id, 'sender_id' => $startups[0]->id, 'body' => 'Absolutely! We are free on Wednesday afternoon.']);

        // ACCEPTED REQUEST
        $acceptedRequest = FundingRequest::firstOrCreate(
            ['startup_user_id' => $startups[1]->id, 'investor_user_id' => $investors[1]->id, 'funding_opportunity_id' => $opportunities[1]->id],
            [
                'requested_amount' => 350000,
                'message' => 'Seeking $350k to launch our FinTech platform in Q2.',
                'status' => 'accepted',
            ]
        );
        Message::create(['funding_request_id' => $acceptedRequest->id, 'sender_id' => $startups[1]->id, 'body' => 'Thank you for accepting our proposal!']);
        Message::create(['funding_request_id' => $acceptedRequest->id, 'sender_id' => $investors[1]->id, 'body' => 'Welcome to the family! Let\'s discuss the terms document.']);
        Message::create(['funding_request_id' => $acceptedRequest->id, 'sender_id' => $startups[1]->id, 'body' => 'Reviewing now. Should we aim for closing by end of Q1?']);

        // REJECTED REQUEST
        $rejectedRequest = FundingRequest::firstOrCreate(
            ['startup_user_id' => $startups[2]->id, 'investor_user_id' => $investors[0]->id, 'funding_opportunity_id' => $opportunities[2]->id],
            [
                'requested_amount' => 600000,
                'message' => 'Looking for $600k for AI research and product development.',
                'status' => 'rejected',
            ]
        );
        Message::create(['funding_request_id' => $rejectedRequest->id, 'sender_id' => $startups[2]->id, 'body' => 'Thanks for considering our application.']);
        Message::create(['funding_request_id' => $rejectedRequest->id, 'sender_id' => $investors[0]->id, 'body' => 'Thanks for sharing. We don\'t see a strong market fit at this time, but would love to reconnect in 12 months!']);
    }
}
