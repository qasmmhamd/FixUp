<?php

/*
|--------------------------------------------------------------------------
| API Routes - FixUp Worker Management System
|--------------------------------------------------------------------------
|
| This file contains all API routes for the FixUp platform.
| The system is divided into:
| - Authentication & User management
| - Orders & Offers system
| - Chat & AI system
| - Notifications system
| - Wallet system
| - Admin dashboard
| - Public endpoints
|
*/

use App\Http\Controllers\Auth\RegisteredWorkersController;
use App\Http\Controllers\Auth\AccountUpgradeController;

use App\Http\Controllers\Profile\UserController;
use App\Http\Controllers\Profile\WorkerController;

use App\Http\Controllers\Order\OrderController;
use App\Http\Controllers\Order\WorkerOrderController;
use App\Http\Controllers\Order\PriceOfferController;
use App\Http\Controllers\Wallet\AcceptPriceOfferController;

use App\Http\Controllers\Notification\DeviceController;
use App\Http\Controllers\Notification\NotificationPriceOfferController;

use App\Http\Controllers\Chat\GuidedConversationController;
use App\Http\Controllers\Chat\AiChatController;
use App\Http\Controllers\Chat\MessageTemplateController;

use App\Http\Controllers\Wallet\AdminWalletController;
use App\Http\Controllers\Wallet\WorkerWalletController;
use App\Http\Controllers\Wallet\JobFeeController;

use App\Http\Controllers\DashboardAdmin\StatisticsController;
use App\Http\Controllers\DashboardAdmin\ManagingAreasController;
use App\Http\Controllers\DashboardAdmin\ManagingCareersController;
use App\Http\Controllers\DashboardAdmin\ManagingWorkersController;
use App\Http\Controllers\DashboardAdmin\ManagingWorkersServiesController;

use App\Http\Controllers\Filters\WorkersFiltersController;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| AUTHENTICATED ROUTES (Sanctum)
|--------------------------------------------------------------------------
| All routes inside this group require valid authentication token.
|
*/

Route::middleware('auth:sanctum')->group(function () {

    /*
    |--------------------------------------------------------------------------
    | USER PROFILE
    |--------------------------------------------------------------------------
    */

    Route::get('/user', fn (Request $request) => $request->user());

    Route::get('/user-profile', [UserController::class, 'show']);
    Route::get('/worker-profile', [WorkerController::class, 'index']);

    Route::put('/update-user-profile', [UserController::class, 'update']);
    Route::put('/update-worker-profile', [WorkerController::class, 'update']);

    /*
    |--------------------------------------------------------------------------
    | ORDERS SYSTEM
    |--------------------------------------------------------------------------
    */

    Route::post('/order', [OrderController::class, 'store']);
    Route::post('/orders/{orderId}/cancel', [OrderController::class, 'cancel']);
    Route::get('/costmer-orders', [OrderController::class, 'index']);

    Route::get('/orders/{orderId}/price-offers', [PriceOfferController::class, 'index']);
    

    /*
    |--------------------------------------------------------------------------
    | PRICE OFFERS ACTIONS
    |--------------------------------------------------------------------------
    */

    Route::post(
        '/orders/{orderId}/offers/{offerId}/accept',
        [AcceptPriceOfferController::class, 'accept']
    );

    /*
    |--------------------------------------------------------------------------
    | NOTIFICATIONS
    |--------------------------------------------------------------------------
    */

    Route::get('/notifications_price-offers', [PriceOfferController::class, 'notifications']);
    Route::get('/notifications_orders', [OrderController::class, 'notifications']);

    Route::post('/device-register', [DeviceController::class, 'store']);

    /*
    |--------------------------------------------------------------------------
    | CHAT SYSTEM (Guided + AI)
    |--------------------------------------------------------------------------
    */

    Route::post('/conversations', [GuidedConversationController::class, 'create']);
    Route::post('/chat/send', [GuidedConversationController::class, 'send']);
    Route::get('/chat/templates/{conversationId}', [GuidedConversationController::class, 'templates']);

    Route::post('/ai-chat', [AiChatController::class, 'ask']);
    Route::get('/ai-chat/messages', [AiChatController::class, 'messages']);

    Route::get('/message-templates', [MessageTemplateController::class, 'getTemplates']);
    Route::get('/Topics', [MessageTemplateController::class, 'topics']);

    /*
    |--------------------------------------------------------------------------
    | WORKER REGISTRATION
    |--------------------------------------------------------------------------
    */

    Route::post('/register-worker', [RegisteredWorkersController::class, 'store']);

    /*
    |--------------------------------------------------------------------------
    | WORKER DASHBOARD (ROLE: WORKER)
    |--------------------------------------------------------------------------
    */

    Route::middleware('role:worker')->group(function () {

        Route::get('/worker/orders', [WorkerOrderController::class, 'workerOrders']);

        Route::get('/worker/offers', [PriceOfferController::class, 'myOffers']);
        Route::get('/worker/offers/accepted', [PriceOfferController::class, 'acceptedOffers']);

        Route::post('/price-offers', [PriceOfferController::class, 'store']);

        Route::get('/worker/wallet', [WorkerWalletController::class, 'wallet']);
        Route::get('/worker/wallet/transactions', [WorkerWalletController::class, 'transactions']);
    });

    /*
    |--------------------------------------------------------------------------
    | ADMIN DASHBOARD (ROLE: ADMIN)
    |--------------------------------------------------------------------------
    */

    Route::middleware('role:admin')
        ->prefix('admin')
        ->group(function () {

            /*
            |--------------------------------------------------------------------------
            | MESSAGE SYSTEM
            |--------------------------------------------------------------------------
            */

            Route::post('/message-templates', [MessageTemplateController::class, 'store']);
            Route::delete('/message-templates/{id}', [MessageTemplateController::class, 'destroyTemplate']);

            Route::post('/message-topics', [MessageTemplateController::class, 'storeTopic']);
            Route::put('/message-topics/{id}', [MessageTemplateController::class, 'updateTopic']);
            Route::delete('/message-topics/{id}', [MessageTemplateController::class, 'destroyTopic']);

            /*
            |--------------------------------------------------------------------------
            | WORKERS MANAGEMENT
            |--------------------------------------------------------------------------
            */

            Route::apiResource('/worker', ManagingWorkersController::class);
            Route::get('/workers/filters', [WorkersFiltersController::class, 'index']);

            /*
            |--------------------------------------------------------------------------
            | SERVICES MANAGEMENT
            |--------------------------------------------------------------------------
            */

            Route::apiResource('/services', ManagingWorkersServiesController::class);

            /*
            |--------------------------------------------------------------------------
            | CAREERS MANAGEMENT
            |--------------------------------------------------------------------------
            */

            Route::apiResource('/careers', ManagingCareersController::class);

            /*
            |--------------------------------------------------------------------------
            | WALLET MANAGEMENT
            |--------------------------------------------------------------------------
            */

            Route::post('/wallet/topup/{workerId}', [AdminWalletController::class, 'topUp']);

            Route::post('/wallet/job-fees', [JobFeeController::class, 'store']);
            Route::put('/wallet/job-fees/{id}', [JobFeeController::class, 'update']);

            /*
            |--------------------------------------------------------------------------
            | STATISTICS & DASHBOARD
            |--------------------------------------------------------------------------
            */

            Route::get('/statistics', [StatisticsController::class, 'index']);

            /*
            |--------------------------------------------------------------------------
            | AREAS MANAGEMENT
            |--------------------------------------------------------------------------
            */

            Route::apiResource('/areas', ManagingAreasController::class);
        });
});

/*
|--------------------------------------------------------------------------
| PUBLIC ROUTES
|--------------------------------------------------------------------------
*/

Route::get('/services', [ManagingWorkersServiesController::class, 'index']);
Route::get('/careers', [ManagingCareersController::class, 'index']);
Route::get('/areas', [ManagingAreasController::class, 'index']);

   




/*
|--------------------------------------------------------------------------
| AUTH ROUTES FILE
|--------------------------------------------------------------------------
*/

require __DIR__ . '/auth.php';