<?php

namespace App\Services\Ai\Dto;

class AdPerformanceAnalysisResult
{
    public function __construct(
        public array $issues,
        public array $recommendations
    ) {
    }

    public static function fromArray(array $payload): self
    {
        return new self(
            $payload['issues'] ?? [],
            $payload['recommendations'] ?? []
        );
    }

    public function toArray(): array
    {
        return [
            'issues' => $this->issues,
            'recommendations' => $this->recommendations,
        ];
    }
}
