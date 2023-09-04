<?php

namespace App\Builders;

class RedirectToMainDomain extends Builder
{
    static function getTitle(): string
    {
        return "Redirect any visitors to the main domain";
    }

    public function configure(): array
    {
        return [
            'mainDomain' => $this->ask('What is the main domain?', $this->options['mainDomain'] ?? null),
        ];
    }
}
