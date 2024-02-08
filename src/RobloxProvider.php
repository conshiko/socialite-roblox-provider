<?php

namespace Conshiko\Laravel\Socialite;

use GuzzleHttp\RequestOptions;
use Laravel\Socialite\Two\AbstractProvider;
use Laravel\Socialite\Two\User;

class RobloxProvider extends AbstractProvider
{
    /**
     * The Roblox OAuth API url.
     *
     * @var string
     */
    protected $url = 'https://apis.roblox.com/oauth/v1';

    /**
     * {@inheritDoc}
     */
    protected $scopes = [
        'openid',
        'profile',
    ];

    /**
     * {@inheritDoc}
     */
    protected $scopeSeparator = ' ';

    /**
     * {@inheritDoc}
     */
    protected function getAuthUrl($state)
    {
        return $this->buildAuthUrlFromBase("{$this->url}/authorize", $state);
    }

    /**
     * {@inheritDoc}
     */
    protected function getTokenUrl()
    {
        return "{$this->url}/token";
    }

    /**
     * {@inheritDoc}
     */
    protected function getUserByToken($token)
    {
        $response = $this->getHttpClient()->get("{$this->url}/userinfo", [
            RequestOptions::HEADERS => [
                'Authorization' => "Bearer {$token}",
            ],
        ]);

        return json_decode((string) $response->getBody(), true);
    }

    /**
     * {@inheritDoc}
     */
    protected function mapUserToObject(array $user)
    {
        return (new User)->setRaw($user)->map([
            'id' => $user['sub'],
            'username' => $user['preferred_username'],
            'display_name' => $user['nickname'],
            'profile_url' => $user['profile'],
            'created_at' => $user['created_at'],
        ]);
    }
}
