<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\View;


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
    public function boot()
    {
        Paginator::useBootstrap();
        // Set timezone to Asia/Jakarta
        Config::set('app.timezone', 'Asia/Jakarta');
        date_default_timezone_set('Asia/Jakarta');

        // Behind a TLS-terminating reverse proxy (Dokploy/Traefik) the container
        // can see the request as http, so asset()/url()/route() emit http:// links.
        // On the https page the browser then BLOCKS those as mixed content
        // (stylesheets/scripts are blockable), leaving the site unstyled.
        // Force https URL generation whenever we're actually serving over https.
        if ($this->shouldForceHttps()) {
            URL::forceScheme('https');
        }

        // Cache-busting version for our static CSS/JS. Docker's `COPY . .`
        // refreshes file mtimes on every image build, so this value changes each
        // deploy — appended as ?v=… to asset links so a CDN (Cloudflare) can't
        // keep serving a stale, modified stylesheet under the same URL.
        View::share('rkv', $this->assetVersion());
    }

    /**
     * A version token that changes on each deploy (mtime of a bundled asset).
     */
    protected function assetVersion(): string
    {
        $probe = public_path('css/new-homepage/rentak-theme.css');

        return is_file($probe) ? (string) filemtime($probe) : (string) date('YmdHi');
    }

    /**
     * Should generated URLs use the https scheme?
     * Deterministic via APP_URL, with a proxy-aware fallback for web requests.
     */
    protected function shouldForceHttps(): bool
    {
        if (str_starts_with((string) config('app.url'), 'https://')) {
            return true;
        }

        if (! $this->app->runningInConsole()) {
            $request = request();

            if ($request->isSecure()) {
                return true;
            }

            if (strtolower((string) $request->header('X-Forwarded-Proto')) === 'https') {
                return true;
            }
        }

        return false;
    }
}
