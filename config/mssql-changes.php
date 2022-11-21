<?php

return [
    'connection' => env('MSSQL_CHANGES_CONNECTION', 'default'),
    'columns-changed-max-width' => env('MSSQL_COLUMNS_CHANGED_MAX_WIDTH', 70),
];
