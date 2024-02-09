<?php
return [
    [
        'user_id' => 1,
        'expired_at' => time() + 3600,
        'token' => 'token-correct'
    ],
    [
        'user_id' => 2,
        'expired_at' => time() + 3600,
        'token' => 'token-correct2'
    ],
    [
        'user_id' => 3,
        'expired_at' => time() + 3600,
        'token' => 'token-correct3'
    ],
];