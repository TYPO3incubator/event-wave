<?php

declare(strict_types=1);

namespace TYPO3Incubator\SurfcampEvents\Service;

class GeolocationApiService
{
    private const URL = 'http://ip-api.com/';

    public function fetchUserData(): ?array
    {
        if ($ip = $this->getUserIp()) {
            return $this->fetchDataByIp($ip);
        }
        return null;
    }

    private function fetchDataByIp(string $ip): ?array
    {
        $response = file_get_contents(self::URL . 'json/' . $ip);
        if (!$response) {
            return null;
        }
        return json_decode($response, true);
    }

    private function getUserIp(): ?string
    {
        $ipaddress = null;
        if (getenv('HTTP_CLIENT_IP'))
            $ipaddress = getenv('HTTP_CLIENT_IP');
        else if(getenv('HTTP_X_FORWARDED_FOR'))
            $ipaddress = getenv('HTTP_X_FORWARDED_FOR');
        else if(getenv('HTTP_X_FORWARDED'))
            $ipaddress = getenv('HTTP_X_FORWARDED');
        else if(getenv('HTTP_FORWARDED_FOR'))
            $ipaddress = getenv('HTTP_FORWARDED_FOR');
        else if(getenv('HTTP_FORWARDED'))
            $ipaddress = getenv('HTTP_FORWARDED');
        else if(getenv('REMOTE_ADDR'))
            $ipaddress = getenv('REMOTE_ADDR');
        return $ipaddress;
    }
}
