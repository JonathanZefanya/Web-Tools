<?php


$features = [
    'custom_url',
    'deep_links',
    'removable_branding',
];

if(settings()->links->biolinks_is_enabled) {
    $features = array_merge($features, [
        'custom_branding',
        'dofollow_is_enabled',
        'leap_link',
        'seo',
        'fonts',
        'custom_css_is_enabled',
        'custom_js_is_enabled',
    ]);
}

$features = array_merge($features, [
    'statistics',
    'temporary_url_is_enabled',
    'cloaking_is_enabled',
    'app_linking_is_enabled',
    'utm',
    'password',
    'sensitive_content',
    'no_ads',
]);

if(settings()->main->api_is_enabled) {
    $features = array_merge($features, [
        'api_is_enabled',
    ]);
}

return $features;

