<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
 */
Route::group(['prefix' => 'consumer', 'middleware' => ['assign.guard:consumer']], function () {
    Route::post('/signUp', 'API\Consumer\AuthController@consumerSignUp');
    Route::post('/signIn', 'API\Consumer\AuthController@consumerSignIn');
    Route::post('/signOut', 'API\Consumer\AuthController@logout');
    Route::post('/forgotPassword', 'API\Consumer\AuthController@forgotPassword');

    Route::middleware(['jwt.auth', 'jwt.verifyClaim'])->group(function () {
        Route::post('/getProfile', 'API\Consumer\UserController@getMyProfile');
        Route::post('/updateProfile', 'API\Consumer\UserController@updteProfile');
        Route::post('/getNotifications', 'API\Consumer\UserController@getMyNotifications');

        Route::post('/addReview', 'API\ReviewController@addReviewConsumer');
        Route::post('/addFavourite', 'API\FavouriteController@addFavourite');
        Route::post('/removeFavourite', 'API\FavouriteController@removeFavourite');
        Route::post('/getAllFavourite', 'API\FavouriteController@getFavourites');

        Route::prefix('address')->group(function () {
            Route::post('/add', 'API\AddressController@addAddress');
            Route::post('/update', 'API\AddressController@updateAddress');
            Route::post('/getAll', 'API\AddressController@getAllAddress');
        });

        Route::group(['prefix'=> 'order'], function(){
            Route::post('/place', 'API\OrderController@placeOrder');
            Route::post('/update', 'API\OrderController@updateOrderStatus');
            Route::post('/myOrders', 'API\OrderController@getMyOrders');
            Route::post('/detail', 'API\OrderController@getOrderDetail');
        });

        Route::post('/getMiscellaneousData', 'API\OrderController@getMiscellaneousData');

    });

    Route::post('/getHomeData', 'API\HomeController@getHomeDataConsumer');
});

// Route::group(['prefix'=> 'order'], function(){
//     Route::post('/place', 'API\OrderController@placeOrder');
//     Route::post('/update', 'API\OrderController@updateOrderStatus');
//     Route::post('/myOrders', 'API\OrderController@getMyOrders');
//     Route::post('/detail', 'API\OrderController@getOrderDetail');
// });

Route::group(['prefix' => 'professional', 'middleware' => ['assign.guard:professional']], function () {
    Route::post('/signUp', 'API\Professional\AuthController@professionalSignUp');
    Route::post('/signIn', 'API\Professional\AuthController@professionalSignIn');
    Route::post('/forgotPassword', 'API\Professional\AuthController@forgotPassword');

    Route::middleware(['jwt.auth', 'jwt.verifyClaim'])->group(function () {
        Route::post('/signOut', 'API\Professional\AuthController@logout');
        Route::post('/getProfile', 'API\Professional\UserController@getMyProfile');
        Route::post('/updateProfile', 'API\Professional\UserController@updteProfile');
        Route::post('/getNotifications', 'API\Professional\UserController@getMyNotifications');

        Route::post('/addReview', 'API\ReviewController@addReviewConsumer');
        Route::post('/addFavourite', 'API\FavouriteController@addFavourite');
        Route::post('/removeFavourite', 'API\FavouriteController@removeFavourite');
        Route::post('/getAllFavourite', 'API\FavouriteController@getFavourites');

        Route::prefix('address')->group(function () {
            Route::post('/add', 'API\AddressController@addAddress');
            Route::post('/update', 'API\AddressController@updateAddress');
            Route::post('/getAll', 'API\AddressController@getAllAddress');
        });

        Route::group(['prefix'=> 'order'], function(){
            Route::post('/place', 'API\OrderController@placeOrder');
            Route::post('/update', 'API\OrderController@updateOrderStatus');
            Route::post('/myOrders', 'API\OrderController@getMyOrders');
            Route::post('/detail', 'API\OrderController@getOrderDetail');
        });

        Route::post('/getMiscellaneousData', 'API\OrderController@getMiscellaneousData');

    });

    Route::post('/getHomeData', 'API\HomeController@getHomeDataProfessional');
});

Route::post('/getPages', 'API\CmsController@getPages');
Route::post('/getPageDetail', 'API\CmsController@getPageDetail');
Route::post('/getDiscounts', 'API\DiscountController@getAllDiscounts');
Route::post('/checkCoupon', 'API\OrderController@checkCouponCode');

Route::group(['prefix' => 'category'], function () {
    Route::post('/getAll', 'API\CategoryController@getAllCategories');
});

Route::group(['prefix' => 'product'], function () {
    Route::post('/consumer', 'API\ProductController@getAllConsumer');
    Route::post('/professional', 'API\ProductController@getAllProfessional');
    Route::post('/byCategoryId', 'API\ProductController@getProductsByCategoryId');
    Route::post('/bySubCategoryId', 'API\ProductController@getProductsBySubCategoryId');
    Route::post('/bySubChildCategoryId', 'API\ProductController@getProductsBySubChildCategoryId');
    Route::post('/detail', 'API\ProductController@getProductDetail');
});

Route::group(['prefix' => 'admin'], function () {
    Route::post('/getListOrders', 'API\OrderController@listOrdersForAdmin');
    Route::post('/orderDetail', 'API\OrderController@orderDetailForAdmin');
    Route::post('/updateOrderStatus', 'API\OrderController@orderStatusUpdateForAdmin');
    Route::post('/updateOrderPaymentStatus', 'API\OrderController@orderPaymentStatusUpdateForAdmin');
    Route::post('/searchOrder', 'API\OrderController@searchOrder');
});
