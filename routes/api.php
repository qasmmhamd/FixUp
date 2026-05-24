<?php

/*
|--------------------------------------------------------------------------
| API Routes - FixUp Worker Management System
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

use App\Http\Controllers\Api\PriceOfferController as ApiPriceOfferController;
use App\Http\Controllers\Auth\AccountUpgradeController;
use App\Http\Controllers\Auth\RegisteredWorkersController;
use App\Http\Controllers\DashboardAdmin\ManagingAreasController;
use App\Http\Controllers\DashboardAdmin\ManagingCareersController;
use App\Http\Controllers\DashboardAdmin\ManagingWorkersController;
use App\Http\Controllers\DashboardAdmin\ManagingWorkersServiesController;
use App\Http\Controllers\Filters\WorkersFiltersController;
use App\Http\Controllers\Profile\UserController;
use App\Http\Controllers\Profile\WorkerController;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Order\OrderController;
use App\Http\Controllers\Order\WorkerOrderController;
use App\Http\Controllers\Order\PriceOfferController;
use App\Http\Controllers\Notification\NotificationPriceOfferController;
use App\Http\Controllers\Notification\DeviceController;
use App\Http\Controllers\Chat\GuidedConversationController;
use App\Http\Controllers\Chat\AiChatController;
use App\Http\Controllers\Chat\MessageTemplateController;
use App\Http\Controllers\Wallet\AdminWalletController;
use App\Http\Controllers\Wallet\WorkerWalletController;
use App\Http\Controllers\Wallet\JobFeeController;
use App\Http\Controllers\Wallet\AcceptPriceOfferController;

use App\Models\PriceOffer;

/*
|--------------------------------------------------------------------------
| Authenticated User Routes
|--------------------------------------------------------------------------
|
| Routes that require authentication via Laravel Sanctum.
| All routes in this group are protected and require a valid API token.
|
*/
Route::middleware('auth:sanctum')->group(function () {

    /*
    |--------------------------------------------------------------------------
    | User Profile Routes
    |--------------------------------------------------------------------------
    */
    Route::get('/user', function (Request $request) {
        return $request->user();
    });
    
    // Get current user's complete profile
    Route::get('/user-profile', [UserController::class, 'show']);
    Route::get('/worker-profile', [WorkerController::class, 'index']);

    
    // Update current user's profile information
    Route::put('/update-user-profile', [UserController::class, 'update']);
    Route::put('/update-worker-profile', [WorkerController::class, 'update']);

    Route::post('/order',[OrderController::class,'store']);
    Route::get('/orders/{orderId}/price-offers', [PriceOfferController::class, 'index']);
    Route::get('/costmer-orders', [OrderController::class, 'index']);
   // Route::get('/notifications/price-offers', [NotificationPriceOfferController::class, 'index']);
   // Route::get('/notifications/price-offers/unread', [NotificationPriceOfferController::class, 'unread']);
   Route::get('/notifications_price-offers', [PriceOfferController::class, 'notifications']);
   Route::get('/notifications_orders', [OrderController::class, 'notifications']);
    Route::post('/device-register', [DeviceController::class, 'store']);

//
    Route::post('/conversations', [GuidedConversationController::class, 'create']);
    Route::post('/chat/send', [GuidedConversationController::class, 'send']);
    Route::get('/chat/templates/{conversationId}', [GuidedConversationController::class, 'templates']);
    Route::post('/ai-chat', [AiChatController::class, 'ask']);
    Route::get('/ai-chat/messages',[AiChatController::class, 'messages']);
    Route::get('/message-templates',[MessageTemplateController::class, 'index']);
    Route::get('/Topics',[MessageTemplateController::class, 'topics']);


    Route::post('/orders/{orderId}/offers/{offerId}/accept', [AcceptPriceOfferController::class,'accept']); 


    /*
    |--------------------------------------------------------------------------
    | Worker Registration Routes
    |--------------------------------------------------------------------------
    */
    
    // Register as a worker (create worker profile)
    Route::post('/register-worker', [RegisteredWorkersController::class, 'store']);
    
   
    Route::middleware('role:worker')->group(function () {
        // Get orders that match the worker's services and career
        Route::get('/worker/orders', [WorkerOrderController::class, 'workerOrders']);
        Route::get('/worker/offers', [PriceOfferController::class, 'myOffers']);
        Route::get('/worker/offers/accepted',[PriceOfferController::class, 'acceptedOffers']);
        // Create a price offer for an order
        Route::post('/price-offers', [PriceOfferController::class, 'store']);


        Route::get('/worker/wallet', [WorkerWalletController::class, 'wallet']);
        Route::get('/worker/wallet/transactions', [WorkerWalletController::class, 'transactions']);
     });
    /*
    |--------------------------------------------------------------------------
    | Admin Routes
    |--------------------------------------------------------------------------
    |
    | Routes that require admin role access.
    | Only users with 'admin' role can access these endpoints.
    |
    */
    Route::middleware('role:admin')->prefix('admin')->group(function () {

        /*
        |--------------------------------------------------------------------------
        | Worker Management Routes
        |--------------------------------------------------------------------------
        */
        
        Route::post('/message-templates', [MessageTemplateController::class, 'store']);
        Route::delete('/message-templates/{id}', [MessageTemplateController::class, 'destroyTemplate']);
        Route::post('/message-topics', [MessageTemplateController::class, 'storeTopic']);
        Route::put('/message-topics/{id}', [MessageTemplateController::class, 'updateTopic']);
        Route::delete('/message-topics/{id}', [MessageTemplateController::class, 'destroyTopic']);
        // Full CRUD operations for workers
        Route::apiResource('/worker', ManagingWorkersController::class);
        
        // Filter and search workers
       Route::get('/workers/filters', [WorkersFiltersController::class, 'index']);
        
        /*
        |--------------------------------------------------------------------------
        | Service Management Routes
        |--------------------------------------------------------------------------
        */
        
        // Full CRUD operations for services
        Route::apiResource('/services', ManagingWorkersServiesController::class);
        
        /*
        |--------------------------------------------------------------------------
        | Career Management Routes
        |--------------------------------------------------------------------------
        */
        
        // Full CRUD operations for careers/professions
        Route::apiResource('/careers', ManagingCareersController::class);


          Route::post('/wallet/topup/{workerId}', [AdminWalletController::class, 'topUp']);

        Route::post('/wallet/job-fees', [JobFeeController::class, 'store']);
        Route::put('/wallet/job-fees/{id}', [JobFeeController::class, 'update']);
        
        /*
        |--------------------------------------------------------------------------
        | Area Management Routes
        |--------------------------------------------------------------------------
        */
        
        // Full CRUD operations for geographical areas
        Route::apiResource('/areas', ManagingAreasController::class);

    });

});

/*
|--------------------------------------------------------------------------
| Authentication Routes
|--------------------------------------------------------------------------
|
| Include the authentication routes (login, register, logout, etc.)
| from the separate auth.php file for better organization.
|
*/
 Route::get('/services', [ManagingWorkersServiesController::class, 'index']);
 Route::get('/careers', [ManagingCareersController::class,'index']);
 Route::get('/areas', [ManagingAreasController::class,'index']);
 Route::get('/test-token', [NotificationPriceOfferController::class, 'testToken']);
 //Route::get('/test-fcm', [NotificationPriceOfferController::class, 'sendNotification']);




 Route::post('/test-fcm', function (Request $request) {

     $user = User::find($request->user_id);

    if (!$user || !$user->fcm_token) {
        return response()->json([
            'error' => 'User not found or fcm_token is null'
        ], 400);
    }

    return app(\App\Services\FcmService::class)->send(
        $user->fcm_token,
        "Test Notification 🔥",
        "هذا إشعار تجريبي من Postman",
        ["type" => "test"]
    );

});
Route::get('/debug-token', function () {
    $user = User::first();

    return [
        'user' => $user,
        'fcm_token' => $user?->fcm_token
    ];
});
Route::get('/me-test', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');




require __DIR__ . '/auth.php';
