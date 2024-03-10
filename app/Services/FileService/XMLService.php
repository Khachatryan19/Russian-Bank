<?php

namespace App\Services\FileService;

use Illuminate\Support\Facades\Log;

class XMLService implements FileService
{
    private array $fileArray;

    public function __construct(string $filePath)
    {
        $maxRetries = 3;
        $retryDelay = 1000;

        $attempt = 0;
        $success = false;

        while ($attempt < $maxRetries && !$success) {
            $fileContent = file_get_contents($filePath);

            if ($fileContent !== false) {
                $xml = simplexml_load_string($fileContent);

                if ($xml !== false) {
                    $json = json_encode($xml);
                    $this->fileArray = json_decode($json, true);
                    $success = true;
                }
            }

            if (!$success) {
                $attempt++;
                usleep($retryDelay * 1000);
            }
        }

        if (!$success) {
            Log::error('Unable to load API data');
        }
    }

    public function read(): array
    {
        return $this->fileArray;
    }

    public function getDate(): array|string
    {
        return str_replace('.', '_', $this->fileArray['@attributes']['Date']);
    }
}
