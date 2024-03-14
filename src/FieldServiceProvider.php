<?php

namespace Stepanenko3\NovaBooleanGroup;

use Illuminate\Support\ServiceProvider;
use Laravel\Nova\Events\ServingNova;
use Laravel\Nova\Nova;

class FieldServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Nova::serving(function (ServingNova $event): void {
            Nova::script('nova-boolean-group-field', __DIR__ . '/../dist/js/field.js');
        });
    }
}
