<?php

namespace App\Providers;

use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use App\Models\Setting;
use Illuminate\Support\Facades\Blade;

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
//        Blade::directive('viteNgrok', function ($paths) {
//            return vite_ngrok($paths);
//        });


        $Settings = new Setting();
        $foots = $Settings->getBase('foot', '.');
        $generals = $Settings->getBase('general', '.');
//        dd($foot,$navigation);
        View::composer('Layouts.superApp', function ($view) use ($foots, $generals) {
            $view->with('foots', $foots)
                ->with('generals', $generals);
//                                ->with('navigation', ['foot' => $ssss, 'navigation' => $aaaa])
        });
    }
}
