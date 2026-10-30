<?php

return [
    'type' => 'game',

    /*
    |--------------------------------------------------------------------------
    | Site Data (previously Jekyll _config.yml/site.data)
    |--------------------------------------------------------------------------
    |
    | This file contains content-ish configuration used in templates
    | (logos, contact info, social links, current jam state, etc.).
    |
    | Access it using: config('jam.key'). Everything not set here comes from the
    | engine's defaults and the preset named by `type`.
    |
    */

    'website' => env('GAMEJAM_WEBSITE', 'https://hagenberg-gamejam.at'),
    'phone_number' => env('GAMEJAM_PHONE_NUMBER', ''),
    'email' => env('GAMEJAM_EMAIL', 'info@hagenberg-gamejam.at'),

    'company' => [
        'name' => env('GAMEJAM_COMPANY_NAME', 'University of Applied Sciences Upper Austria – Department of Digital Media'),
        'url' => env('GAMEJAM_COMPANY_URL', 'https://fh-ooe.at/'),
        'address' => env('GAMEJAM_COMPANY_ADDRESS', 'Softwarepark 11, 4232 Hagenberg, Austria'),
    ],

    // These are media file names (stored under _media/)
    'branding' => [
        'logo_dark' => env('GAMEJAM_LOGO_DARK', 'hagenberg_game_jam_logo_black.svg'),
        'logo_light' => env('GAMEJAM_LOGO_LIGHT', 'hagenberg_game_jam_logo_white.svg'),
    ],

    'social' => [
        'instagram' => env('GAMEJAM_INSTAGRAM_URL', 'https://www.instagram.com/hagenberg_gamejam/'),
        'github' => env('GAMEJAM_GITHUB_URL', 'https://github.com/Hagenberg-Jams/hagenberg-gamejam.at/'),
        'discord' => env('GAMEJAM_DISCORD_URL', 'https://discord.gg/kh2rXBj8nr'),
    ],

    'latest_jam' => env('GAMEJAM_LATEST_JAM', '2025'),

    'voting' => [
        'active' => filter_var(env('GAMEJAM_VOTING_ACTIVE', 'false'), FILTER_VALIDATE_BOOL),
        'url' => env('GAMEJAM_VOTING_URL', 'https://www.instagram.com/hagenberg_gamejam/'),
        'deadline' => env('GAMEJAM_VOTING_DEADLINE', '2026-01-25T23:59:00'),
    ],

    'registration' => [
        'next_jam' => env('GAMEJAM_NEXT_JAM', '2025'),
        'active' => filter_var(env('GAMEJAM_REGISTRATION_ACTIVE', 'false'), FILTER_VALIDATE_BOOL),
        'url' => env('GAMEJAM_REGISTRATION_URL', 'https://forms.office.com/e/J0cpwNdunh'),
        'deadline' => env('GAMEJAM_REGISTRATION_DEADLINE', '2025-12-07T23:59:00'),
    ],

    /*
    |--------------------------------------------------------------------------
    | Game download base URL (e.g. Cloudflare R2 public bucket)
    |--------------------------------------------------------------------------
    |
    | When set, download links point to {base}/games/{year}/{file} instead of
    | /games/{year}/{file}. Use for production when games are served from R2.
    | Leave empty for local or same-origin /games/.
    |
    */
    'downloads' => [
        'base_url' => env('GAMES_BASE_URL', null),
    ],
];
