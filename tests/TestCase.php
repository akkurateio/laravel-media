<?php

namespace Akkurate\LaravelMedia\Tests;

use Akkurate\LaravelCore\Providers\LaravelAccessServiceProvider;
use Akkurate\LaravelCore\Models\User;
use Akkurate\LaravelCore\Providers\LaravelAdminServiceProvider;
use Akkurate\LaravelCore\Models\Account;
use Akkurate\LaravelCore\Models\Language;
use Akkurate\LaravelBackComponents\LaravelBackComponentsServiceProvider;
use Akkurate\LaravelMedia\LaravelMediaServiceProvider;
use Akkurate\LaravelSearch\LaravelSearchServiceProvider;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Kris\LaravelFormBuilder\FormBuilderServiceProvider;
use Orchestra\Testbench\TestCase as OrchestraTestCase;
use Spatie\JsonApiPaginate\JsonApiPaginateServiceProvider;
use Spatie\Permission\PermissionServiceProvider;
use Cviebrock\EloquentSluggable\ServiceProvider as EloquentSluggableServiceProvider;

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

        $this->user->preference()->create([
            'preferenceable_type' => 'Akkurate\LaravelCore\Models\User',
            'preferenceable_id' => $this->user->id,
            'target' => 'both',
            'pagination' => 30,
            'language_id' => Language::where('locale', 'fr')->first()->id
        ]);
    }

    protected function getPackageProviders($app)
    {
        return [
            LaravelMediaServiceProvider::class,
            LaravelAccessServiceProvider::class,
            PermissionServiceProvider::class,
            JsonApiPaginateServiceProvider::class,
            LaravelBackComponentsServiceProvider::class,
            LaravelSearchServiceProvider::class,
            FormBuilderServiceProvider::class,
            LaravelAdminServiceProvider::class,
            EloquentSluggableServiceProvider::class,
        ];
    }

    protected function setUpDatabase()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->string('email')->nullable();
            $table->string('password')->nullable();
            $table->timestamps();
        });

        $this->loadMigrationsFrom(__DIR__ . '/../vendor/akkurate/laravel-core/database/laravel-access/migrations');
        $this->seed(\Akkurate\LaravelCore\Database\Seeders\Access\DatabaseSeeder::class);

        $this->loadMigrationsFrom(__DIR__ . '/../vendor/akkurate/laravel-core/database/laravel-admin/migrations');
        $this->seed(\Akkurate\LaravelCore\Database\Seeders\Admin\LanguagesTableSeeder::class);
        $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');
    }

    protected function createUser()
    {
        $account = Account::create([
            'name' => 'Account',
            'email' => 'account@email.com',
        ]);

        User::forceCreate([
            'firstname' => 'User',
            'lastname' => 'Lastname',
            'email' => 'user@email.com',
            'password' => 'test',
            'account_id' => $account->id,
        ]);
    }
}
