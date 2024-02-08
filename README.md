# 🛰️ Socialite Roblox Provider
> *A Roblox provider for Laravel Socialite.*

## Installation
```sh
composer require conshiko/socialite-roblox-provider
```

## Usage
The package registers a Socialite driver with the name of `roblox`.

Before using the driver, create an OAuth application in Roblox’s Creator Hub:
https://create.roblox.com/dashboard/credentials?activeTab=OAuthTab

Set your client ID and client secret as environment variables, and then reference them in your **config/services.php** file. You will also need to add a redirect URL to your application.

```php
<?php

// config/services.php

return [

    // Any other services

    'roblox' => [
        'client_id' => env('ROBLOX_CLIENT_ID'),
        'client_secret' => env('ROBLOX_CLIENT_SECRET'),
        'redirect' => env('ROBLOX_REDIRECT_URI'),
    ],

];
```

```sh
# .env

# Any other configuration

ROBLOX_CLIENT_ID=
ROBLOX_CLIENT_SECRET=
ROBLOX_REDIRECT_URI="${APP_URL}/roblox/authenticate"
```

### Authenticating
Create a controller to redirect and handle the access token callback:

```php
<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

class RobloxController extends Controller
{
    /**
     * Handle a Roblox OAuth login.
     * 
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function authenticate()
    {
        $driver = Socialite::driver('roblox');

        if (!$request->has('code')) {
            return $driver->redirect();
        }

        /**
         * $rbx->id
         * $rbx->username
         * $rbx->display_name
         * $rbx->profile_url
         * $rbx->created_at
         */
        $rbx = $driver->user();
        $user = User::updateOrCreate([
            'roblox_id' => $rbx->id,
        ], [
            'username' => $rbx->username,
            'display_name' => $rbx->display_name,
        ]);

        Auth::login($user, true);

        return redirect(RouteServiceProvider::HOME);
    }
}

```
