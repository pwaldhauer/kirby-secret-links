# Kirby Secret Links

A small niche plugin for all of you who got completely or partially secret Kirby pages! 

The plugin provides a function to create sharable links that are only valid for one page and expire after a given time.

I especially wanted a plugin that generates links that contain all needed information and do not need to save anything anywhere, so the secret link has an encrypted payload that contains the expiration time and the slug of the page requested. The urls are getting quite long, because Base64 gets quite large. 

It is not a plug and play thingy and needs some adjustments, but I guess, if you are running a secret Kirby page you are already a bit on the more custom side.

It uses the `illuminate/encryption` library, which has quite a lot of dependencies. I may remove this dependency in a future version, but I really wanted a battle proven encryption solution and not some code copied from Stack Overflow.

*I do not guarantee that this is actually safe*

## Installation

- Install the plugin
- In `config.php` add the following config option:
```php
'secret-links.encryptionKey' => '32 character encryption key',
```

- In your `index.php` add:
```php
// Needed because the Illuminate framework brings its own e function.
define('KIRBY_HELPER_E', false);
```

- Somewhere in your `config.php` you may have a `route:before` hook that manages your secret-ness. Add the following:
```php
'hooks' => [
        
        // […]
        
        'route:before' => function ($route, $path, $method) {
        
            // Swap 's' for a parameter name of your choice
            if (isset($_GET['s'])) {
                if (PwaSecretLinks::checkShareLink($_GET['s'], $path)) {
                    return true;
                }
            }
            
            // […]
        }
        
        // […]
]
```

- Somewhere use `<?= PwaSecretLinks::generateShareLink($page) ?>` to return the sharable url. You can also specify the parameter name (default: `s`) and the seconds until expiry (default: 86400): `<?= PwaSecretLinks::generateShareLink($page, 'x', 300) ?>` 

## License

MIT