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

    'website' => 'https://hagenberg-gamejam.at',
    'phone_number' => '',
    'email' => 'info@hagenberg-gamejam.at',

    'company' => [
        'name' => 'University of Applied Sciences Upper Austria – Department of Digital Media',
        'url' => 'https://fh-ooe.at/',
        'address' => 'Softwarepark 11, 4232 Hagenberg, Austria',
    ],

    // These are media file names (stored under _media/)
    'branding' => [
        'logo_dark' => 'hagenberg_game_jam_logo_black.svg',
        'logo_light' => 'hagenberg_game_jam_logo_white.svg',
    ],

    'social' => [
        'instagram' => 'https://www.instagram.com/hagenberg_gamejam/',
        'github' => 'https://github.com/Hagenberg-Jams/hagenberg-gamejam.at/',
        'discord' => 'https://discord.gg/kh2rXBj8nr',
    ],

    'latest_jam' => '2025',

    'voting' => [
        'active' => false,
        'url' => 'https://www.instagram.com/hagenberg_gamejam/',
        'deadline' => '2026-01-25T23:59:00',
    ],

    'registration' => [
        'next_jam' => '2026',
        'active' => false,
        // Empty: the form on this site at /register.
        'url' => '',
        'deadline' => '2026-12-06T23:59:00',
        'form' => [
            'enabled' => true,
            // A team lead and up to 5 members.
            'team_size' => [1, 6],
            'onsite_capacity' => 100,
            'fields' => [
                'participation' => true,
                'discord' => true,
                'programs' => true,
                'comment' => true,
            ],
            'programs_label' => 'Study programme',
            'programs' => [
                'Digital Arts Bachelor (DA.ba)',
                'Media Technology and Design (MTD.ba)',
                'Digital Arts Master (DA.ma)',
                'Interactive Media Master (IM.ma)',
                'AI-Driven Computer Science (ACS.ba)',
                'Artificial Intelligence Solutions (AIS.ba)',
                'Automotive Computing (AC.ba)',
                'Design of Digital Products (DDP.ba)',
                'Hardware-Software-Design (HSD.ba)',
                'Communication and Knowledge Media Bachelor (KWM.ba)',
                'Medical and Bioinformatics (MBI.ba)',
                'Mobile Computing Bachelor (MC.ba)',
                'Secure Information Systems Bachelor (SI.ba)',
                'Software Engineering Bachelor (SE.ba)',
                'Artificial Intelligence & Data Engineering (ADE.ma)',
                'Data Science and Engineering (DSE.ma)',
                'Digital Energy Solutions (DES.ma)',
                'Embedded Systems Design (ESD.ma)',
                'Energy Informatics (ENI.ma)',
                'Human-Centered Computing (HCC.ma)',
                'Information Engineering and Management (IEM.ma)',
                'Information Security Management (ISM.ma)',
                'Communication and Knowledge Media Master (KWM.ma)',
                'Mobile Computing Master (MC.ma)',
                'Robotics Systems Engineering (RSE.ma)',
                'Secure Information Systems Master (SI.ma)',
                'Software Engineering Master (SE.ma)',
                'Other UAP campus (Linz, Wels, Steyr)',
                'Alumni (graduates of any programme, not studying anymore)',
                'External (not studying at UAP)',
            ],
            'nickname_label' => 'Nickname',
            // Organisers are in the GitHub secret JAM_REGISTRATION_NOTIFY.
            'from' => 'noreply@hagenberg-gamejam.at',
            'retention_days' => 90,
            'turnstile_site_key' => '0x4AAAAAAF1-azvKHw7ApI_n',
            'access' => [
                'team' => 'hagenberg-jams',
                'aud' => '3e38e837a89d35c05f4e48869cc8c12a5987782edb18dfbed257cebe9b642337',
            ],
            'texts' => [
                'intro' => 'Register your team for the Hagenberg Game Jam 2026! A team consists of a team lead and up to 5 members. We can host up to 100 people on site, and remote participation via Discord is possible too.',
                'confirmed' => 'Join our Discord to get the latest news and your team roles: https://discord.gg/kh2rXBj8nr',
                'declined' => '',
            ],
        ],
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
