<?php

return [
    'epoch' => env('SNOWFLAKE_EPOCH', '2024-01-01 00:00:00'),

    'worker_id' => env('SNOWFLAKE_WORKER_ID', 1),

    'datacenter_id' => env('SNOWFLAKE_DATACENTER_ID', 1),
];
