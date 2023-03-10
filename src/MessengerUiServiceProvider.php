<?php

namespace Baskarcm\TziChatUi;

use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Routing\Router;
use Illuminate\Support\ServiceProvider;
use Baskarcm\TziChatUi\Commands\PublishCommand;

class MessengerUiServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any package services.
     *
     * @return void
     *
     * @throws BindingResolutionException
     */
    public function boot(): void
    {
        $this->mergeConfigFrom(__DIR__.'/../config/messenger-ui.php', 'tzi-chat-ui');

        $router = $this->app->make(Router::class);

        $router->group($this->webRouteConfiguration(), function () {
            $this->loadRoutesFrom(__DIR__.'/../routes/web.php');
        });

        $router->group($this->webRouteConfiguration(true), function () {
            $this->loadRoutesFrom(__DIR__.'/../routes/invite.php');
        });

        $this->loadViewsFrom(__DIR__.'/../resources/views', 'messenger');

        if ($this->app->runningInConsole()) {
            $this->bootForConsole();
        }
    }

    /**
     * Console-specific booting.
     *
     * @return void
     */
    private function bootForConsole(): void
    {
        $this->commands([
            PublishCommand::class,
        ]);

        $this->publishes([
            __DIR__.'/../config/messenger-ui.php' => config_path('messenger-ui.php'),
        ], 'tzi-chat-ui.config');

        $this->publishes([
            __DIR__.'/../resources/views' => base_path('resources/views/vendor/messenger'),
        ], 'tzi-chat-ui.views');

        $this->publishes([
            __DIR__.'/../public' => public_path('vendor/messenger'),
        ], 'tzi-chat-ui.assets');
    }

    /**
     * Register any package services.
     *
     * @return void
     */
    public function register(): void
    {
        //
    }

    /**
     * Get the Messenger API route group configuration array.
     *
     * @param  bool  $invite
     * @return array
     */
    private function webRouteConfiguration(bool $invite = false): array
    {
        return [
            'domain' => config('tzi-chat-ui.routing.domain'),
            'prefix' => trim(config('tzi-chat-ui.routing.prefix'), '/'),
            'middleware' => $invite
                ? config('tzi-chat-ui.routing.invite_middleware')
                : config('tzi-chat-ui.routing.middleware'),
        ];
    }
}
