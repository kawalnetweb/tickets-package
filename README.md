## Quick Installation

composer require netweb/tickets

## Include in the provider

....
Netweb\Tickets\TicketServiceProvider::class,

## Publish Assets (Optional)

php artisan vendor:publish --tag=ticket-config

## Config ENV

CLIENT_TOKEN = 'Your client code in tickets project'
PROJECT_CODE = 'Your Project code in tickets project'

TICKET_SFTP_HOST
TICKET_SFTP_USERNAME
TICKET_SFTP_PASSWORD
TICKET_SFTP_ROOT = 'path to where tickets attachments need to upload'
