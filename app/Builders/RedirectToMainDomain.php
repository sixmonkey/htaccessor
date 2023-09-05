<?php

namespace App\Builders;

use App\Builders\Base\Builder;

class RedirectToMainDomain extends Builder
{
    /**
     * @var bool Whether this builder needs mod_rewrite or not
     */
    public static bool $needsModRewrite = true;

    /**
     * @var int The position of this builder in the htaccess file
     */
    public static int $position = 0;

    /**
     * @return string The readable title of this builder
     */
    static function getTitle(): string
    {
        return "Redirect any visitors to the main domain";
    }

    /**
     * @return array The config options for this builder
     */
    public function configure(): array
    {
        return [
            'mainDomain' => $this->ask('What is the main domain?', $this->options['mainDomain'] ?? null),
            'protocol' => $this->confirm('Do you want to redirect to https?', true) ? 'https' : 'http',
        ];
    }
}
