<?php

namespace App\Providers;

use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Laravel\Passport\Passport;
use App\Policies\GarmentPolicy;
use App\Models\Garment;
use App\Policies\LitemPolicy;
use App\Models\Litem;
use App\Policies\ExpensePolicy;
use App\Models\Expense;
use App\Models\Camera;
use App\Policies\CameraPolicy;
use App\Models\Comment;
use App\Policies\CommentPolicy;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        Garment::class => GarmentPolicy::class,
        Litem::class => LitemPolicy::class,
        Expense::class => ExpensePolicy::class,
        Camera::class => CameraPolicy::class,
        Comment::class => CommentPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();
		Passport::routes();
    }
}
