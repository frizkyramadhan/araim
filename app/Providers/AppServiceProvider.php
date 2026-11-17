<?php

namespace App\Providers;

use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
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
        // Windows Apache mpm_winnt + mod_php shares one process across apps.
        // Hero's Dotenv putenv() can leave API_KEY/DB_* in the process; ARAIM
        // Dotenv will not overwrite them. Re-apply this app's .env before config use.
        $this->reclaimOwnEnvironment();
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Paginator::useBootstrap();
        Schema::defaultStringLength(191);
    }

    private function reclaimOwnEnvironment(): void
    {
        $path = base_path('.env');
        if (! is_readable($path)) {
            return;
        }

        $wanted = [
            'API_KEY', 'ARAIM_API_KEY',
            'DB_CONNECTION', 'DB_HOST', 'DB_PORT', 'DB_DATABASE', 'DB_USERNAME', 'DB_PASSWORD',
            'HERO_API_URL', 'HERO_API_TOKEN',
        ];

        foreach (file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) as $line) {
            $line = ltrim($line);
            if ($line === '' || str_starts_with($line, '#') || ! str_contains($line, '=')) {
                continue;
            }
            [$name, $value] = explode('=', $line, 2);
            $name = trim($name);
            if (! in_array($name, $wanted, true)) {
                continue;
            }
            $value = trim($value);
            if (
                strlen($value) >= 2
                && (($value[0] === '"' && str_ends_with($value, '"'))
                    || ($value[0] === "'" && str_ends_with($value, "'")))
            ) {
                $value = substr($value, 1, -1);
            }
            putenv($name.'='.$value);
            $_ENV[$name] = $value;
            $_SERVER[$name] = $value;
        }

        $apiKey = $_ENV['ARAIM_API_KEY'] ?? $_ENV['API_KEY'] ?? null;
        if (is_string($apiKey) && $apiKey !== '') {
            config(['services.api.key' => $apiKey]);
        }

        $default = config('database.default', 'mysql');
        config([
            "database.connections.{$default}.host" => $_ENV['DB_HOST'] ?? config("database.connections.{$default}.host"),
            "database.connections.{$default}.port" => $_ENV['DB_PORT'] ?? config("database.connections.{$default}.port"),
            "database.connections.{$default}.database" => $_ENV['DB_DATABASE'] ?? config("database.connections.{$default}.database"),
            "database.connections.{$default}.username" => $_ENV['DB_USERNAME'] ?? config("database.connections.{$default}.username"),
            "database.connections.{$default}.password" => $_ENV['DB_PASSWORD'] ?? config("database.connections.{$default}.password"),
        ]);
        DB::purge($default);
    }
}
