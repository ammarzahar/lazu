<?php

namespace App\Services\Ai\Dto;

class DailyCmoBriefResult
{
    public function __construct(public array $decisions)
    {
    }

    public static function fromArray(array $payload): self
    {
        return new self($payload['decisions'] ?? []);
    }

    public function toArray(): array
    {
        return [
            'decisions' => $this->decisions,
        ];
    }
}
