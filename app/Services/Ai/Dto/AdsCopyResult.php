<?php

namespace App\Services\Ai\Dto;

class AdsCopyResult
{
    public function __construct(
        public string $headline,
        public string $primaryText,
        public string $cta,
        public array $variations
    ) {
    }

    public static function fromArray(array $payload): self
    {
        return new self(
            (string) $payload['headline'],
            (string) $payload['primary_text'],
            (string) $payload['cta'],
            $payload['variations'] ?? []
        );
    }

    public function toArray(): array
    {
        return [
            'headline' => $this->headline,
            'primary_text' => $this->primaryText,
            'cta' => $this->cta,
            'variations' => $this->variations,
        ];
    }
}
