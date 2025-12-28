<?php

namespace Database\Seeders;

use App\Models\MarketingEvent;
use Illuminate\Database\Seeder;

class MarketingEventsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $leadTimes = [14, 7, 1];

        $events = [
            ['name' => '11.11 Sale', 'event_date' => '2025-11-11', 'region' => 'global', 'category' => 'sale'],
            ['name' => '12.12 Sale', 'event_date' => '2025-12-12', 'region' => 'global', 'category' => 'sale'],
            ['name' => 'Black Friday', 'event_date' => '2025-11-28', 'region' => 'global', 'category' => 'sale'],
            ['name' => 'Black Friday', 'event_date' => '2026-11-27', 'region' => 'global', 'category' => 'sale'],
            ['name' => 'Black Friday', 'event_date' => '2027-11-26', 'region' => 'global', 'category' => 'sale'],
            ['name' => 'Christmas', 'event_date' => '2025-12-25', 'region' => 'global', 'category' => 'festive'],
            ['name' => 'Christmas', 'event_date' => '2026-12-25', 'region' => 'global', 'category' => 'festive'],
            ['name' => 'Christmas', 'event_date' => '2027-12-25', 'region' => 'global', 'category' => 'festive'],
            ['name' => 'Ramadan Start', 'event_date' => '2026-02-17', 'region' => 'my', 'category' => 'festive'],
            ['name' => 'Raya Aidilfitri', 'event_date' => '2026-03-20', 'region' => 'my', 'category' => 'festive'],
        ];

        foreach ($events as $event) {
            MarketingEvent::query()->firstOrCreate(
                [
                    'name' => $event['name'],
                    'event_date' => $event['event_date'],
                    'region' => $event['region'],
                ],
                [
                    'category' => $event['category'],
                    'default_lead_time_days' => $leadTimes,
                ]
            );
        }
    }
}
