<?php

namespace App\Providers;

use App\Lib\Constants\ErrorCodes;
use App\Lib\Constants\HttpStatusCodes;
use App\Lib\Response\ResponseBuilder;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Schema::defaultStringLength(191);
        app()->make(ResponseBuilder::class)->registerErrors([

            // AUTHORIZATION ERRORS
            [
                ErrorCodes::INVALID_USER_CREDENTIALS,
                'Invalid user credentials',
                HttpStatusCodes::UNAUTHORIZED,
            ],
            [
                ErrorCodes::UNAUTHENTICATED,
                'Unauthenticated user',
                HttpStatusCodes::UNAUTHORIZED,
            ],
            [
                ErrorCodes::USER_NOT_AUTHORIZED,
                'Unauthorized user',
                HttpStatusCodes::UNAUTHORIZED,
            ],

            //GARMENTS
            [
                ErrorCodes::GARMENT_NOT_FOUND,
                'Garment not found',
                HttpStatusCodes::NOT_FOUND,
            ],
            [
                ErrorCodes::INVALID_GARMENT,
                'Invalid garment',
                HttpStatusCodes::BAD_REQUEST,
            ],
            [
                ErrorCodes::FAILED_TO_CREATE_GARMENT,
                'Garment can\'t be created',
                HttpStatusCodes::INTERNAL_SERVER_ERROR,
            ],
            [
                ErrorCodes::FAILED_TO_UPDATE_GARMENT,
                'Garment cant\'t be updated',
                HttpStatusCodes::INTERNAL_SERVER_ERROR,
            ],
            [
                ErrorCodes::FAILED_TO_DELETE_GARMENT,
                'Garment cant\'t be deleted',
                HttpStatusCodes::INTERNAL_SERVER_ERROR,
            ],

            //USERS
            [
                ErrorCodes::USER_NOT_FOUND,
                'User not found',
                HttpStatusCodes::NOT_FOUND,
            ],
            [
                ErrorCodes::INVALID_USER,
                'Invalid user',
                HttpStatusCodes::BAD_REQUEST,
            ],
            [
                ErrorCodes::FAILED_TO_CREATE_USER,
                'User can\'t be created',
                HttpStatusCodes::INTERNAL_SERVER_ERROR,
            ],
            [
                ErrorCodes::FAILED_TO_UPDATE_USER,
                'User can\'t be updated',
                HttpStatusCodes::INTERNAL_SERVER_ERROR,
            ],
            [
                ErrorCodes::FAILED_TO_DELETE_USER,
                'User can\'t be deleted',
                HttpStatusCodes::INTERNAL_SERVER_ERROR,
            ],
            //EXPENSES
            [
                ErrorCodes::EXPENSE_NOT_FOUND,
                'Expense not found',
                HttpStatusCodes::NOT_FOUND,
            ],
            [
                ErrorCodes::INVALID_EXPENSE,
                'Invalid expense',
                HttpStatusCodes::BAD_REQUEST,
            ],
            [
                ErrorCodes::FAILED_TO_CREATE_EXPENSE,
                'Expense can\'t be created',
                HttpStatusCodes::INTERNAL_SERVER_ERROR,
            ],
            [
                ErrorCodes::FAILED_TO_UPDATE_EXPENSE,
                'Expense can\'t be updated',
                HttpStatusCodes::INTERNAL_SERVER_ERROR,
            ],
            [
                ErrorCodes::FAILED_TO_DELETE_EXPENSE,
                'Expense can\'t be deleted',
                HttpStatusCodes::INTERNAL_SERVER_ERROR,
            ],
            //CAMERA
            [
                ErrorCodes::CAMERA_NOT_FOUND,
                'Camera image not found',
                HttpStatusCodes::NOT_FOUND,
            ],
            [
                ErrorCodes::INVALID_CAMERA,
                'Invalid camera image',
                HttpStatusCodes::BAD_REQUEST,
            ],
            [
                ErrorCodes::FAILED_TO_CREATE_CAMERA,
                'Camera image can\'t be created',
                HttpStatusCodes::INTERNAL_SERVER_ERROR,
            ],
            [
                ErrorCodes::FAILED_TO_UPDATE_CAMERA,
                'Camera image can\'t be updated',
                HttpStatusCodes::INTERNAL_SERVER_ERROR,
            ],
            [
                ErrorCodes::FAILED_TO_DELETE_CAMERA,
                'Camera image can\'t be deleted',
                HttpStatusCodes::INTERNAL_SERVER_ERROR,
            ],

            //SPENDING_CATEGORIES
            [
                ErrorCodes::SPENDING_CATEGORY_NOT_FOUND,
                'Spending category not found',
                HttpStatusCodes::NOT_FOUND,
            ],
            [
                ErrorCodes::INVALID_SPENDING_CATEGORY,
                'Invalid spending category',
                HttpStatusCodes::BAD_REQUEST,
            ],
            [
                ErrorCodes::FAILED_TO_CREATE_SPENDING_CATEGORY,
                'Spending category can\'t be created',
                HttpStatusCodes::INTERNAL_SERVER_ERROR,
            ],
            [
                ErrorCodes::FAILED_TO_UPDATE_SPENDING_CATEGORY,
                'Spending category can\'t be updated',
                HttpStatusCodes::INTERNAL_SERVER_ERROR,
            ],
            [
                ErrorCodes::FAILED_TO_DELETE_SPENDING_CATEGORY,
                'Spending category can\'t be deleted',
                HttpStatusCodes::INTERNAL_SERVER_ERROR,
            ],

            //COMMENTS
            [
                ErrorCodes::COMMENT_NOT_FOUND,
                'Coment not found',
                HttpStatusCodes::NOT_FOUND,
            ],
            [
                ErrorCodes::INVALID_COMMENT,
                'Invalid comment:',
                HttpStatusCodes::BAD_REQUEST,
            ],
            [
                ErrorCodes::FAILED_TO_CREATE_COMMENT,
                'Comment can\'t be created',
                HttpStatusCodes::INTERNAL_SERVER_ERROR,
            ],
            [
                ErrorCodes::FAILED_TO_UPDATE_COMMENT,
                'Comment can\'t be updated',
                HttpStatusCodes::INTERNAL_SERVER_ERROR,
            ],
            [
                ErrorCodes::FAILED_TO_DELETE_COMMENT,
                'Comment can\'t be deleted',
                HttpStatusCodes::INTERNAL_SERVER_ERROR,
            ],
        ]);
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(ResponseBuilder::class, function () {
            return new ResponseBuilder();
        });
    }
}
