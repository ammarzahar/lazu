<?php

namespace App\Services\Ai\Dto;

class CampaignPlanResult
{
    public function __construct(
        public array $plan,
        public array $offer,
        public array $copy
    ) {
    }

    public static function fromArray(array $payload): self
    {
        return new self(
            $payload['plan'] ?? [],
            $payload['offer'] ?? [],
            $payload['copy'] ?? []
        );
    }

    public function toArray(): array
    {
        return [
            'plan' => $this->plan,
            'offer' => $this->offer,
            'copy' => $this->copy,
        ];
    }
}
