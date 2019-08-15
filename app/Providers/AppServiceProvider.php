<?php

namespace App\Providers;

use Illuminate\Support\Carbon;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;
use Laravel\Passport\Client;
use Laravel\Passport\Passport;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        Passport::ignoreMigrations();
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->formatDates();
        $this->checkDatabaseDebugging();
        $this->usePassportUuid();
    }

    /**
     * Format dates returned by the API.
     * 
     * @return void
     */
    private function formatDates()
    {
        Carbon::serializeUsing(function ($carbon) {
            return $carbon->toIso8601String();
        });
    }

    /**
     * Check and send databse debugging info to standard output.
     * 
     * @return void
     */
    private function checkDatabaseDebugging()
    {
        if (config('database.debug') == 'true' && !App::environment('production')) {
            DB::listen(function ($query) {
                $q = "Query: (".$query->time." ms)\r\n";
                $q .= $query->sql."\r\n";
                $q .= implode(', ', $query->bindings)."\r\n\r\n";
                $q .= "-----------------------\r\n\r\n";
                echo $q;
            });
        }
    }

    /**
     * Use UUID for Laravel Passport clients.
     * 
     * @return void
     */
    private function usePassportUuid()
    {
        // Auto generate uuid when creating new oauth client
        Client::creating(function (Client $client) {
            $client->incrementing = false;
            $client->id = Str::uuid();
        });

        // Turn off auto incrementing for passport clients
        Client::retrieved(function (Client $client) {
            $client->incrementing = false;
        });
    }
}
