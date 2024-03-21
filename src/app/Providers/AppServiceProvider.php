<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Auth\Notifications\ResetPassword;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Events\QueryExecuted;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // SQLログ出力
        DB::listen(function (QueryExecuted $query) {
            $sql = $query->connection
                ->getQueryGrammar()
                ->substituteBindingsIntoRawSql(
                    $query->sql,
                    $query->bindings
                );
            Log::debug('[' . $query->time . ' ms] ' . $sql);
        });

        // パスワードリセット用URLの書き換え
        ResetPassword::createUrlUsing(function ($user, string $token) {
            return env('HOME_URL') . '/reset_password?email=' . $user->email . '&token=' . $token;
        });
    }
}
