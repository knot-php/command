<?php
return [
    'log_manager' => [
        'log_enabled' => true,
    ],
    'logs' => [
        'command' => [
            'type' => 'file',
            'enabled' => true,
            'options' => [
                'logs_dir'   => '%LOGS_DIR%/command/%DATE_Y4%/%DATE_M%/%DATE_D%',
                'file_name'  => '%DATE_Y4%-%DATE_M%-%DATE_D%_%DATE_H24%-%DATE_I%-%DATE_S%_%DATE_U%_command.log',
                'log_levels' => ['ALL'],
                'log_format' => '%DATE_Y4%-%DATE_M%-%DATE_D% %DATE_H24%:%DATE_I%:%DATE_S% [%LEVEL%] %MESSAGE%       @%FILENAME%(%LINE%)',
            ],
        ],
        'error' => [
            'type' => 'file',
            'enabled' => true,
            'options' => [
                'logs_dir'   => '%LOGS_DIR%/command/%DATE_Y4%/%DATE_M%/%DATE_D%',
                'file_name'  => '%DATE_Y4%-%DATE_M%-%DATE_D%_%DATE_H24%-%DATE_I%-%DATE_S%_%DATE_U%_error.log',
                'log_levels' => ['E'],
                'log_format' => '%DATE_Y4%-%DATE_M%-%DATE_D% %DATE_H24%:%DATE_I%:%DATE_S% [%LEVEL%] %MESSAGE%       @%FILENAME%(%LINE%)',
            ],
        ],
    ],
];