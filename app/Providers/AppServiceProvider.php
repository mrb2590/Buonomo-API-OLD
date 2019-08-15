<?php

namespace App\Providers;

use Illuminate\Support\Carbon;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
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
}
