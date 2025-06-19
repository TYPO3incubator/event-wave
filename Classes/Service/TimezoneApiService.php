<?php

declare(strict_types=1);

namespace TYPO3Incubator\SurfcampEvents\Service;

use TYPO3\CMS\Core\Utility\DebugUtility;

class TimezoneApiService
{
    private const API_URL = 'https://timeapi.io/api/time/current/';

    public function getByCoordinates(float $lat, float $long): ?array
    {
        $url = self::API_URL . 'coordinate?latitude=' . $lat . '&longitude=' . $long;
        $response = file_get_contents($url);
        if (!$response) {
            return null;
        }
        return json_decode($response, true);
    }
}
