<?php

namespace App\Services\Ai;

use App\Models\AiCall;
use App\Models\BusinessProfile;
use App\Services\Ai\Dto\AdPerformanceAnalysisResult;
use App\Services\Ai\Dto\AdsCopyResult;
use App\Services\Ai\Dto\CampaignPlanResult;
use App\Services\Ai\Dto\DailyCmoBriefResult;
use App\Services\Ai\Dto\OfferAnalysisResult;
use App\Services\Ai\Dto\OfferUpgradeResult;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class AiService
{
    public function generateOfferAnalysis(BusinessProfile $businessProfile, string $offerText): OfferAnalysisResult
    {
        $payload = $this->requestJson(
            endpoint: 'offer_analysis',
            messages: [
                $this->systemMessage('You are a senior growth marketer. Return JSON only.'),
                $this->userMessage($this->buildOfferAnalysisPrompt($businessProfile, $offerText)),
            ],
            validator: fn (array $data) => $this->validateOfferAnalysis($data)
        );

        return OfferAnalysisResult::fromArray($payload);
    }

    public function generateOfferUpgrades(BusinessProfile $businessProfile, string $offerText): OfferUpgradeResult
    {
        $payload = $this->requestJson(
            endpoint: 'offer_upgrades',
            messages: [
                $this->systemMessage('You are a direct-response copy strategist. Return JSON only.'),
                $this->userMessage($this->buildOfferUpgradePrompt($businessProfile, $offerText)),
            ],
            validator: fn (array $data) => $this->validateOfferUpgrades($data)
        );

        return OfferUpgradeResult::fromArray($payload);
    }

    public function generateAdsCopy(BusinessProfile $businessProfile, array $offer, string $style, string $language): AdsCopyResult
    {
        $payload = $this->requestJson(
            endpoint: 'ads_copy',
            messages: [
                $this->systemMessage('You are a performance copywriter. Return JSON only.'),
                $this->userMessage($this->buildAdsCopyPrompt($businessProfile, $offer, $style, $language)),
            ],
            validator: fn (array $data) => $this->validateAdsCopy($data)
        );

        return AdsCopyResult::fromArray($payload);
    }

    public function analyzeAdPerformance(BusinessProfile $businessProfile, array $metricsSummary): AdPerformanceAnalysisResult
    {
        $payload = $this->requestJson(
            endpoint: 'ad_performance',
            messages: [
                $this->systemMessage('You are a performance analyst. Return JSON only.'),
                $this->userMessage($this->buildAdPerformancePrompt($businessProfile, $metricsSummary)),
            ],
            validator: fn (array $data) => $this->validateAdPerformance($data)
        );

        return AdPerformanceAnalysisResult::fromArray($payload);
    }

    public function generateDailyCmoBrief(array $context): DailyCmoBriefResult
    {
        $payload = $this->requestJson(
            endpoint: 'daily_cmo_brief',
            messages: [
                $this->systemMessage('You are an AI CMO for SME founders. Return JSON only.'),
                $this->userMessage($this->buildDailyBriefPrompt($context)),
            ],
            validator: fn (array $data) => $this->validateDailyBrief($data)
        );

        return DailyCmoBriefResult::fromArray($payload);
    }

    public function generateCampaignPlan(array $event, BusinessProfile $businessProfile, ?array $recentSalesSignals, string $objective): CampaignPlanResult
    {
        $payload = $this->requestJson(
            endpoint: 'campaign_plan',
            messages: [
                $this->systemMessage('You are a campaign planner and copy strategist. Return JSON only.'),
                $this->userMessage($this->buildCampaignPlanPrompt($event, $businessProfile, $recentSalesSignals, $objective)),
            ],
            validator: fn (array $data) => $this->validateCampaignPlan($data)
        );

        return CampaignPlanResult::fromArray($payload);
    }

    private function requestJson(string $endpoint, array $messages, callable $validator): array
    {
        $model = config('services.openai.model', 'gpt-4o-mini');
        $payload = [
            'model' => $model,
            'messages' => $messages,
            'temperature' => 0.4,
            'response_format' => ['type' => 'json_object'],
        ];

        $content = $this->callOpenAi($payload);
        [$data, $errors] = $this->decodeAndValidate($content, $validator);

        if ($errors !== []) {
            $repairPayload = $payload;
            $repairPayload['messages'] = [
                $this->systemMessage('Fix the JSON to match the schema. Return JSON only.'),
                $this->userMessage("Invalid JSON output:\n{$content}\nErrors:\n" . implode("\n", $errors)),
            ];
            $content = $this->callOpenAi($repairPayload);
            [$data, $errors] = $this->decodeAndValidate($content, $validator);
        }

        if ($errors !== []) {
            throw new \RuntimeException('AI response failed validation for ' . $endpoint . ': ' . implode('; ', $errors));
        }

        $this->logAiCall($endpoint, $model, $payload, $data);

        return $data;
    }

    private function callOpenAi(array $payload): string
    {
        $response = Http::withToken(config('services.openai.key'))
            ->acceptJson()
            ->post('https://api.openai.com/v1/chat/completions', $payload);

        if (! $response->successful()) {
            Log::error('OpenAI request failed', ['status' => $response->status(), 'body' => $response->body()]);
            throw new \RuntimeException('OpenAI request failed.');
        }

        return (string) data_get($response->json(), 'choices.0.message.content', '');
    }

    private function decodeAndValidate(string $content, callable $validator): array
    {
        $data = json_decode($content, true);
        if (! is_array($data)) {
            return [[], ['Invalid JSON.']];
        }

        $errors = $validator($data);

        return [$data, $errors];
    }

    private function logAiCall(string $endpoint, string $model, array $payload, array $response): void
    {
        Log::info('AI call', [
            'endpoint' => $endpoint,
            'model' => $model,
            'payload' => $payload,
            'response' => $response,
        ]);

        AiCall::query()->create([
            'user_id' => auth()->id(),
            'model' => $model,
            'endpoint' => $endpoint,
            'payload' => [
                'request' => $payload,
                'response' => $response,
            ],
        ]);
    }

    private function systemMessage(string $content): array
    {
        return ['role' => 'system', 'content' => $content];
    }

    private function userMessage(string $content): array
    {
        return ['role' => 'user', 'content' => $content];
    }

    private function buildOfferAnalysisPrompt(BusinessProfile $profile, string $offerText): string
    {
        return <<<PROMPT
Business:
{$this->profileSummary($profile)}

Current offer:
{$offerText}

Return JSON with:
score: integer 0-100
findings: array of short strings
suggestions: array of short strings
PROMPT;
    }

    private function buildOfferUpgradePrompt(BusinessProfile $profile, string $offerText): string
    {
        return <<<PROMPT
Business:
{$this->profileSummary($profile)}

Current offer:
{$offerText}

Return JSON with:
bundle: array of ideas
bonus: array of ideas
urgency: array of ideas
risk_reversal: array of ideas
PROMPT;
    }

    private function buildAdsCopyPrompt(BusinessProfile $profile, array $offer, string $style, string $language): string
    {
        $offerText = json_encode($offer, JSON_UNESCAPED_SLASHES);

        return <<<PROMPT
Business:
{$this->profileSummary($profile)}

Offer details:
{$offerText}

Style: {$style}
Language: {$language}

Return JSON with:
headline: string
primary_text: string
cta: string
variations: array of strings
PROMPT;
    }

    private function buildAdPerformancePrompt(BusinessProfile $profile, array $metricsSummary): string
    {
        $metrics = json_encode($metricsSummary, JSON_UNESCAPED_SLASHES);

        return <<<PROMPT
Business:
{$this->profileSummary($profile)}

Metrics summary:
{$metrics}

Return JSON with:
issues: array of short strings
recommendations: array of short strings
PROMPT;
    }

    private function buildDailyBriefPrompt(array $context): string
    {
        $contextJson = json_encode($context, JSON_UNESCAPED_SLASHES);

        return <<<PROMPT
Context:
{$contextJson}

Return JSON with:
decisions: array (max 3) of objects {action, target, reason, priority}
PROMPT;
    }

    private function buildCampaignPlanPrompt(array $event, BusinessProfile $profile, ?array $recentSalesSignals, string $objective): string
    {
        $eventJson = json_encode($event, JSON_UNESCAPED_SLASHES);
        $salesSignals = json_encode($recentSalesSignals ?? [], JSON_UNESCAPED_SLASHES);

        return <<<PROMPT
Event:
{$eventJson}

Business:
{$this->profileSummary($profile)}

Recent sales signals:
{$salesSignals}

Objective: {$objective}

Return JSON with:
plan: array of steps
offer: array of offer elements
copy: array of copy angles
PROMPT;
    }

    private function profileSummary(BusinessProfile $profile): string
    {
        return "Name: {$profile->business_name}\n"
            . "Type: {$profile->business_type}\n"
            . "Product/Service: {$profile->product_or_service}\n"
            . "Price: {$profile->price_min} - {$profile->price_max}\n"
            . "Gross margin %: " . ($profile->gross_margin_pct ?? 'n/a') . "\n"
            . "Audience: {$profile->target_audience}\n"
            . "Main channel: {$profile->main_channel}\n"
            . "Monthly objective: {$profile->monthly_objective}";
    }

    private function validateOfferAnalysis(array $data): array
    {
        $errors = [];

        if (! isset($data['score']) || ! is_numeric($data['score'])) {
            $errors[] = 'score must be numeric';
        }
        if (! isset($data['findings']) || ! is_array($data['findings'])) {
            $errors[] = 'findings must be array';
        }
        if (! isset($data['suggestions']) || ! is_array($data['suggestions'])) {
            $errors[] = 'suggestions must be array';
        }

        return $errors;
    }

    private function validateOfferUpgrades(array $data): array
    {
        $errors = [];
        foreach (['bundle', 'bonus', 'urgency', 'risk_reversal'] as $key) {
            if (! isset($data[$key]) || ! is_array($data[$key])) {
                $errors[] = "{$key} must be array";
            }
        }

        return $errors;
    }

    private function validateAdsCopy(array $data): array
    {
        $errors = [];
        foreach (['headline', 'primary_text', 'cta'] as $key) {
            if (! isset($data[$key]) || ! is_string($data[$key])) {
                $errors[] = "{$key} must be string";
            }
        }
        if (! isset($data['variations']) || ! is_array($data['variations'])) {
            $errors[] = 'variations must be array';
        }

        return $errors;
    }

    private function validateAdPerformance(array $data): array
    {
        $errors = [];
        if (! isset($data['issues']) || ! is_array($data['issues'])) {
            $errors[] = 'issues must be array';
        }
        if (! isset($data['recommendations']) || ! is_array($data['recommendations'])) {
            $errors[] = 'recommendations must be array';
        }

        return $errors;
    }

    private function validateDailyBrief(array $data): array
    {
        $errors = [];
        if (! isset($data['decisions']) || ! is_array($data['decisions'])) {
            $errors[] = 'decisions must be array';
        }

        if (isset($data['decisions']) && count($data['decisions']) > 3) {
            $errors[] = 'decisions must be max 3';
        }

        return $errors;
    }

    private function validateCampaignPlan(array $data): array
    {
        $errors = [];
        foreach (['plan', 'offer', 'copy'] as $key) {
            if (! isset($data[$key]) || ! is_array($data[$key])) {
                $errors[] = "{$key} must be array";
            }
        }

        return $errors;
    }
}
