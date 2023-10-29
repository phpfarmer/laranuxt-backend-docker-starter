<?php

namespace App\Utils;

final class AppCommon
{
    static function extractValues($keys, $sourceArray): array
    {
        $result = array();

        foreach ($keys as $key) {
            if (isset($sourceArray[$key])) {
                $result[$key] = $sourceArray[$key];
            }
        }

        return $result;
    }
}
