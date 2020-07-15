<?php

namespace App\Providers;

use App\Models\Camera;
use App\Models\Comment;
use App\Models\Destination;
use App\Models\Expense;
use App\Models\Garment;
use App\Models\Litem;
use App\Models\Place;
use App\Models\SpendingCategory;
use App\Policies\CameraPolicy;
use App\Policies\CommentPolicy;
use App\Policies\DestinationPolicy;
use App\Policies\ExpensePolicy;
use App\Policies\GarmentPolicy;
use App\Policies\LitemPolicy;
use App\Policies\PlacePolicy;
use App\Policies\SpendingCategoryPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Laravel\Passport\Passport;

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
        SpendingCategory::class => SpendingCategoryPolicy::class,
        Destination::class => DestinationPolicy::class,
        Place::class => PlacePolicy::class,
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
