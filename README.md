# LAZU

LAZU is an AI CMO MVP for SME founders. It focuses on next-step marketing decisions (not dashboards), offer analysis, ad copy generation, and basic Meta Ads performance insights.

## MVP Features

- Business profiling wizard (post-register gate)
- AI Offer Analyzer + Offer Builder ideas
- AI Ads Copy Generator (BM + EN)
- Daily CMO Brief (max 3 actions)
- Marketing Calendar & Campaign Planner
- Meta Ads integration (mock + real provider flag)
- Weekly marketing report email

## Tech Stack

- Laravel 12, PHP 8.2+
- MySQL (SQLite works for dev)
- Blade + Tailwind (via Breeze)
- Laravel Queues + Scheduler

## Setup

```bash
composer install
copy .env.example .env
php artisan key:generate
php artisan migrate --seed
npm install
npm run build
php artisan serve
```

## Required .env Keys

```
OPENAI_API_KEY=
OPENAI_MODEL=gpt-4o-mini
ADS_PROVIDER=mock
META_GRAPH_VERSION=v20.0
APP_TIMEZONE=Asia/Kuala_Lumpur
```

## Scheduler

Add the Laravel scheduler to your cron:

```
* * * * * php artisan schedule:run >> /dev/null 2>&1
```

## Development Notes

- Meta Ads mock provider works without a token.
- Real Meta Graph provider uses stored `ad_account_id` + `access_token`.
- AI requests are rate-limited (`throttle:ai`).
