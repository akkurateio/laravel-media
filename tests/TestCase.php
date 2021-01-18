<?php

namespace Akkurate\LaravelMedia\Tests;

use Akkurate\LaravelAccountSubmodule\Models\User;
use Akkurate\LaravelBackComponents\LaravelBackComponentsServiceProvider;
use Akkurate\LaravelMedia\LaravelMediaServiceProvider;
use Akkurate\LaravelSearch\LaravelSearchServiceProvider;
use Cviebrock\EloquentSluggable\ServiceProvider as EloquentSluggableServiceProvider;
use Kris\LaravelFormBuilder\FormBuilderServiceProvider;
use Orchestra\Testbench\TestCase as OrchestraTestCase;
use Spatie\JsonApiPaginate\JsonApiPaginateServiceProvider;
use Spatie\Permission\PermissionServiceProvider;

class TestCase extends OrchestraTestCase
{
    protected $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->setUpDatabase();
        $this->createUser();
        $this->user = User::first();
        auth()->login($this->user);
    }

    protected function getPackageProviders($app)
    {
        return [
            LaravelMediaServiceProvider::class,
            PermissionServiceProvider::class,
            JsonApiPaginateServiceProvider::class,
            LaravelBackComponentsServiceProvider::class,
            LaravelSearchServiceProvider::class,
            FormBuilderServiceProvider::class,
            EloquentSluggableServiceProvider::class,
        ];
    }

    protected function setUpDatabase()
    {
        $this->loadMigrationsFrom(__DIR__ . '/../vendor/akkurateio/laravel-account-submodule/database/migrations');
        $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');
    }

    protected function createUser()
    {
        $user = User::factory()->create();
        $user->preference()->create();
    }
}
