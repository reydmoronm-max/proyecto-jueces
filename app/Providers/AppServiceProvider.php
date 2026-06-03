<?php

namespace App\Providers;

use App\Models\Citaciones;
use Carbon\Carbon;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

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
        View::composer('*', function ($view) {
            $todayCount = Citaciones::whereDate('fecha_citacion', Carbon::today())
                ->where('estatus', true)
                ->count();

            $view->with('citacionesHoyPendientesCount', $todayCount);
        });
    }
}
