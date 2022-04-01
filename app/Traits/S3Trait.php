<?php

namespace App\Traits;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Storage;

trait S3Trait
{
    function getUrlS3($prefix_url, $name_file): string | null
    {
        if ($name_file) {
            $url = "{$prefix_url}/{$name_file}";
            $client = Storage::disk('s3')->getClient();
            $bucket = Config::get('filesystems.disks.s3.bucket');
            $command = $client->getCommand('GetObject', [
                'Bucket' => $bucket,
                'Key' => $url
            ]);
            $request = $client->createPresignedRequest($command, '+20 minutes');
            return  (string)$request->getUri();
        }
        return null;
    }
}
