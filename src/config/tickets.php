<?php
return [
    'client_token' => env('CLIENT_TOKEN'),
    'project_code' => env('PROJECT_CODE'),
    'api_url' => env('API_URL','https://crm.ixoraeducation.com/api'),
    'sftp' => [
        'host' => env('TICKET_SFTP_HOST'),
        'username' => env('TICKET_SFTP_USERNAME'),
        'password' => env('TICKET_SFTP_PASSWORD'),
        'root' => env('TICKET_SFTP_ROOT'),
    ],
];
?>
