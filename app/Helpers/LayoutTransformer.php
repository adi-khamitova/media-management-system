<?php

declare(strict_types=1);

namespace App\Helpers;

class LayoutTransformer
{
    public static function fromRequest(array $layout): array
    {
        $result = [];
        foreach ($layout as $value) {
            $result[] = [$value['x'], $value['y'], $value['z'], $value['type']];
        }
        return $result;
    }

}
