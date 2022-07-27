<?php

use \Illuminate\Encryption\Encrypter;

class PwaSecretLinks
{
    public static function checkShareLink(string $hash, string $expectedSlug): bool
    {
        $encrypter = new Encrypter(option('secret-links.encryptionKey'), 'aes-256-gcm');
        try {
            $payload = $encrypter->decryptString($hash);
        } catch (\Exception $x) {
            return false;
        }

        [$timestamp, $slug] = explode('|', $payload);
        if ($slug !== $expectedSlug) {
            return false;
        }

        if ($timestamp < time()) {
            return false;
        }

        return true;
    }

    public static function generateShareLink(\Kirby\Cms\Page $page, string $paramName = 's', int $expiryInSeconds = 86400): string
    {
        $payload = sprintf('%s|%s', time() + $expiryInSeconds, $page->id());
        $encrypter = new Encrypter(option('secret-links.encryptionKey'), 'aes-256-gcm');

        return sprintf('%s?%s=%s', $page->url(), $paramName, $encrypter->encryptString($payload));
    }
}

