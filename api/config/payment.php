<?php

return [
    'redis' => [
        'pay' => 'queue:pay:log',
        'iban' => [
            'individual' => 'queue:iban:individual:log',
            'company' => 'queue:iban:company:log',
        ],
    ],
];
