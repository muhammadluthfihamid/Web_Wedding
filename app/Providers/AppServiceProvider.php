<?php

namespace App\Providers;

use Carbon\Carbon;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\URL;
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
        if ($this->app->environment('production') || request()->header('x-forwarded-proto') === 'https') {
            URL::forceScheme('https');
        }

        Paginator::useBootstrap();
        Carbon::setLocale('id');

        // Share dynamic store/platform name across all Blade templates & config
        $storeName = \App\Models\Setting::getStoreName();
        config(['app.name' => $storeName]);
        view()->share('store_name', $storeName);

        // Share recent RSVPs, Wishes, and Orders with the admin layout
        view()->composer('admin.layouts.admin', function ($view) {
            $recentRsvps = collect();
            $unreadRsvpsCount = 0;
            $recentWishes = collect();
            $unreadWishesCount = 0;
            $recentOrders = collect();
            $pendingOrdersCount = 0;

            if (\Schema::hasTable('rsvps')) {
                $recentRsvps = \App\Models\Rsvp::orderBy('created_at', 'desc')->take(5)->get();

                $rsvpLastReadAt = session('rsvp_last_read_at');
                if ($rsvpLastReadAt) {
                    $unreadRsvpsCount = \App\Models\Rsvp::where('created_at', '>', $rsvpLastReadAt)->count();
                } else {
                    $unreadRsvpsCount = \App\Models\Rsvp::where('created_at', '>=', now()->subDays(7))->count();
                }
            }

            if (\Schema::hasTable('wishes')) {
                $recentWishes = \App\Models\Wish::orderBy('created_at', 'desc')->take(5)->get();

                $wishLastReadAt = session('wish_last_read_at');
                if ($wishLastReadAt) {
                    $unreadWishesCount = \App\Models\Wish::where('created_at', '>', $wishLastReadAt)->count();
                } else {
                    $unreadWishesCount = \App\Models\Wish::where('created_at', '>=', now()->subDays(7))->count();
                }
            }

            if (\Schema::hasTable('orders')) {
                $recentOrders = \App\Models\Order::with(['user', 'package'])->where('status', 'pending')->latest()->take(5)->get();

                $ordersLastReadAt = session('orders_last_read_at');
                if ($ordersLastReadAt) {
                    $pendingOrdersCount = \App\Models\Order::where('status', 'pending')->where('created_at', '>', $ordersLastReadAt)->count();
                } else {
                    $pendingOrdersCount = \App\Models\Order::where('status', 'pending')->count();
                }
            }

            $view->with([
                'recentRsvps' => $recentRsvps,
                'unreadRsvpsCount' => $unreadRsvpsCount,
                'recentWishes' => $recentWishes,
                'unreadWishesCount' => $unreadWishesCount,
                'recentOrders' => $recentOrders,
                'pendingOrdersCount' => $pendingOrdersCount,
            ]);
        });
    }
}