<?php

declare(strict_types=1);

namespace App\Services\Parsers;

interface Parser
{
    public function parse(string $link, string $path, string $complexName);
}
