<?php

namespace App\Services\Ai\Dto;

class OfferAnalysisResult
{
    public function __construct(
        public int $score,
        public array $findings,
        public array $suggestions
    ) {
    }

    public static function fromArray(array $payload): self
    {
        return new self(
            (int) $payload['score'],
            $payload['findings'] ?? [],
            $payload['suggestions'] ?? []
        );
    }

    public function toArray(): array
    {
        return [
            'score' => $this->score,
            'findings' => $this->findings,
            'suggestions' => $this->suggestions,
        ];
    }
}
