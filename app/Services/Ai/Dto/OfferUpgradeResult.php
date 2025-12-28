<?php

namespace App\Services\Ai\Dto;

class OfferUpgradeResult
{
    public function __construct(
        public array $bundle,
        public array $bonus,
        public array $urgency,
        public array $riskReversal
    ) {
    }

    public static function fromArray(array $payload): self
    {
        return new self(
            $payload['bundle'] ?? [],
            $payload['bonus'] ?? [],
            $payload['urgency'] ?? [],
            $payload['risk_reversal'] ?? []
        );
    }

    public function toArray(): array
    {
        return [
            'bundle' => $this->bundle,
            'bonus' => $this->bonus,
            'urgency' => $this->urgency,
            'risk_reversal' => $this->riskReversal,
        ];
    }
}
