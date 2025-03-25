<?php

return [
    'gpt-4' => [
        'name' => 'GPT 4',
        'max_tokens' => 8192,
        'type' => 'chat_completions',
    ],
    'gpt-3.5-turbo' => [
        'name' => 'Turbo',
        'max_tokens' => 4096,
        'type' => 'chat_completions',
    ],
    'text-davinci-003' => [
        'name' => 'Davinci',
        'max_tokens' => 4000,
        'type' => 'completions',
    ],
    'text-curie-001' => [
        'name' => 'Curie',
        'max_tokens' => 2048,
        'type' => 'completions',
    ],
    'text-babbage-001' => [
        'name' => 'Babbage',
        'max_tokens' => 2048,
        'type' => 'completions',
    ],
    'text-ada-001' => [
        'name' => 'Ada',
        'max_tokens' => 2048,
        'type' => 'completions',
    ],
];
