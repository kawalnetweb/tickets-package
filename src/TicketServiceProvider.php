<?php
namespace Netweb\Tickets;

use Illuminate\Support\ServiceProvider;

Class TicketServiceProvider extends ServiceProvider
{
    public function boot(){
        // load routes from package
        $this->loadRoutesFrom(__DIR__.'/routes/web.php');
        // load viewa from package
        $this->loadViewsFrom(__DIR__.'/views','tickets');
        // Merge Config File
        $this->mergeConfigFrom(__DIR__.'/config/tickets.php', 'tickets');

        // publishing config
        $this->publishes([
            __DIR__.'/config/tickets.php' => config_path('tickets.php'),
        ], 'config');
    }

}
?>
