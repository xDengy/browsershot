<?php

declare(strict_types=1);

namespace App\Services\Parsers;

use Spatie\ArrayToXml\ArrayToXml;

abstract class Parser
{
    abstract public function complex(string $link, string $path, string $complexName);

    protected function save(array $data, string $path)
    {
        $results = (new ArrayToXml($data, 'complexes', true, 'UTF-8'))
            ->prettify()
            ->toXml();

        file_put_contents($path . '.xml', $results);
    }
}
