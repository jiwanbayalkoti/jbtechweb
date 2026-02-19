<?php

namespace Database\Seeders;

use App\Models\Plan;
use Illuminate\Database\Seeder;

class PlanSeeder extends Seeder
{
    public function run(): void
    {
        $plans = [
            [
                'name' => 'Starter',
                'slug' => 'starter',
                'description' => 'For small businesses',
                'monthly_price' => 9.99,
                'yearly_price' => 99.99,
                'trial_days' => 14,
                'max_pages' => 5,
                'max_media' => 50,
                'max_users' => 2,
                'is_active' => true,
                'sort_order' => 1,
            ],
            [
                'name' => 'Professional',
                'slug' => 'professional',
                'description' => 'For growing businesses',
                'monthly_price' => 29.99,
                'yearly_price' => 299.99,
                'trial_days' => 14,
                'max_pages' => 20,
                'max_media' => 200,
                'max_users' => 5,
                'is_active' => true,
                'sort_order' => 2,
            ],
            [
                'name' => 'Enterprise',
                'slug' => 'enterprise',
                'description' => 'For large organizations',
                'monthly_price' => 99.99,
                'yearly_price' => 999.99,
                'trial_days' => 30,
                'max_pages' => 100,
                'max_media' => 1000,
                'max_users' => 25,
                'is_active' => true,
                'sort_order' => 3,
            ],
        ];

        foreach ($plans as $plan) {
            Plan::updateOrCreate(['slug' => $plan['slug']], $plan);
        }
    }
}
