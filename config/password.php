<?php

use App\Rules\UniquePasswordHistory;

return [
    'strong_password_rules' => ['required', 'string', 'min:8', new UniquePasswordHistory],
    'password_change_days' => 1,
    'max_password_history' => 2,
];
