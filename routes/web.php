<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
 */

Route::get('/', function () {
    return view('welcome');
});

Route::post('/login', 'Auth\LoginController@login')->name('login');
Route::post('/logout', 'Auth\LoginController@logout')->name('logout');
Route::post('/send-notification', 'FCMController@sendToAll')->name('sendNotification');

Route::group(['prefix' => 'admin-panel', 'middleware' => ['assign.guard:web']], function () {
    Route::get('/', 'AdminController@showLogin')->name('admin-panel');
    Route::middleware(['admin'])->group(function () {
    Route::get('/dashboard', 'AdminController@showDashbaord')->name('adminDashboard');
    Route::post('/updateDeliveryCharges', 'AdminController@updateDeliveryCharges')->name('updateDeliveryCharges');


    Route::group(['middleware' => ['web', 'permission:manage-categories']], function () {

        Route::group(['prefix' => 'category'], function () {
            Route::get('/', 'CategoryController@index')->name('listCategories');
            Route::get('/create', 'CategoryController@create')->name('createCategory');
            Route::post('/store', 'CategoryController@store')->name('storeCategory');
            Route::get('/edit/{id}', 'CategoryController@edit')->name('editCategory');
            Route::post('/update', 'CategoryController@update')->name('updateCategory');
            Route::post('/deactivate', 'CategoryController@destroy')->name('deleteCategory');
            Route::get('/exportPdf', 'CategoryController@exportPdf')->name('exportPdfCategory');
            Route::get('/products/{id}', 'CategoryController@getProductsByCategoryId')->name('productByCategory');
        });
        Route::group(['prefix' => 'sub-category'], function () {
            Route::get('/', 'SubCategoryController@index')->name('listSubCategories');
            Route::get('/create', 'SubCategoryController@create')->name('createSubCategory');
            Route::post('/store', 'SubCategoryController@store')->name('storeSubCategory');
            Route::get('/edit/{id}', 'SubCategoryController@edit')->name('editSubCategory');
            Route::post('/update', 'SubCategoryController@update')->name('updateSubCategory');
            Route::post('/deactivate', 'SubCategoryController@destroy')->name('deleteSubCategory');
            Route::get('/products/{id}', 'SubCategoryController@getProductsBySubCatId')->name('productBySC');

        });

        Route::group(['prefix' => 'sub-child-category'], function () {
            Route::get('/', 'SubChildCategoryController@index')->name('listSubChildCategories');
            Route::get('/create', 'SubChildCategoryController@create')->name('createSubChildCategory');
            Route::post('/store', 'SubChildCategoryController@store')->name('storeSubChildCategory');
            Route::get('/edit/{id}', 'SubChildCategoryController@edit')->name('editSubChildCategory');
            Route::post('/update', 'SubChildCategoryController@update')->name('updateSubChildCategory');
            Route::post('/deactivate', 'SubChildCategoryController@destroy')->name('deleteSubChildCategory');
            Route::get('/products/{id}', 'SubChildCategoryController@getProductsBySubChildCatId')->name('productBySCC');
        });
    });


    Route::group(['middleware' => ['web', 'permission:manage-products']], function () {

        Route::group(['prefix' => 'product'], function () {
            Route::get('/', 'ProductController@index')->name('listProducts');
            Route::get('/create', 'ProductController@create')->name('createProduct');
            Route::post('/store', 'ProductController@store')->name('storeProduct');
            Route::get('/edit/{id}', 'ProductController@edit')->name('editProduct');
            Route::post('/update', 'ProductController@update')->name('updateProduct');
            Route::post('/deactivate', 'ProductController@destroy')->name('deleteProduct');
            Route::post('/removeProductImage', 'ProductImageController@destroy')->name('removeProductImage');

            Route::get('/topSellingProductReport', 'OrderController@getTopSellingProducts')->name('getTopSellingProducts');
            Route::get('/exportTopSellingProductReport', 'OrderController@exportTopSellingProducts')->name('exportTopSellingProducts');

            Route::get('/productDetail/{id}', 'ProductController@show')->name('productDetail');
        });
    });

    Route::group(['middleware' => ['web', 'permission:manage-reviews']], function () {

        Route::group(['prefix' => 'review'], function () {
            Route::get('/', 'ReviewController@index')->name('listReviews');
            Route::get('/approve/{id}', 'ReviewController@update')->name('approveReview');
            Route::post('/delete', 'ReviewController@destroy')->name('deleteReview');
        });
    });



    Route::group(['middleware' => ['web', 'permission:manage-offers']], function () {
        Route::group(['prefix' => 'offer'], function () {
            Route::get('/', 'OfferController@index')->name('listOffers');
            Route::get('/create', 'OfferController@create')->name('createOffer');
            Route::post('/store', 'OfferController@store')->name('storeOffer');
            Route::get('/edit/{id}', 'OfferController@edit')->name('editOffer');
            Route::post('/update', 'OfferController@update')->name('updateOffer');
            Route::get('/detail/{id}', 'OfferController@show')->name('showOffer');
            Route::post('/deactivate', 'OfferController@destroy')->name('deleteOffer');
        });
    });

    Route::group(['middleware' => ['web', 'permission:manage-banners']], function () {
        Route::group(['prefix' => 'banner'], function () {
            Route::get('/', 'BannerController@index')->name('listBanners');
            Route::get('/create', 'BannerController@create')->name('createBanner');
            Route::post('/store', 'BannerController@store')->name('storeBanner');
            Route::get('/edit/{id}', 'BannerController@edit')->name('editBanner');
            Route::post('/update', 'BannerController@update')->name('updateBanner');
            Route::post('/delete', 'BannerController@destroy')->name('deleteBanner');
        });
    });

    Route::group(['middleware' => ['web', 'permission:manage-discounts']], function () {

        Route::group(['prefix' => 'discount'], function () {
            Route::get('/', 'DiscountController@index')->name('listDiscounts');
            Route::get('/create', 'DiscountController@create')->name('createDiscount');
            Route::post('/store', 'DiscountController@store')->name('storeDiscount');
            Route::get('/edit/{id}', 'DiscountController@edit')->name('editDiscount');
            Route::post('/update', 'DiscountController@update')->name('updateDiscount');
            Route::post('/delete', 'DiscountController@destroy')->name('deleteDiscount');
        });
    });


    Route::group(['middleware' => ['web', 'permission:manage-users']], function () {

        Route::prefix('consumer')->group(function () {
            Route::get('/list', 'ConsumerController@index')->name('listConsumers');
            Route::get('/edit/{id}', 'ConsumerController@edit')->name('editConsumer');
            Route::post('/update', 'ConsumerController@update')->name('updateConsumer');
            Route::post('/deactivate', 'ConsumerController@destroy')->name('deleteConsumer');
            Route::post('/activate', 'ConsumerController@activateConsumer')->name('activateConsumer');
            Route::get('/detail/{id}', 'ConsumerController@show')->name('consumerDetail');
            Route::get('/deleted', 'ConsumerController@deletedConsumers')->name('deletedConsumers');

            Route::post('/search', 'ConsumerController@search')->name('searchConsumers');
            Route::post('/export', 'ConsumerController@exportConsumerAsPDF')->name('exportConsumers');

        });

        Route::prefix('professional')->group(function () {

            Route::get('/list', 'ProfessionalController@index')->name('listProfessionals');
            Route::get('/create', 'ProfessionalController@create')->name('createProfessional');
            Route::post('/store', 'ProfessionalController@store')->name('storeProfessional');
            Route::get('/edit/{id}', 'ProfessionalController@edit')->name('editProfessional');
            Route::post('/update', 'ProfessionalController@update')->name('updateProfessional');
            Route::post('/deactivate', 'ProfessionalController@destroy')->name('deleteProfessional');
            Route::post('/activate', 'ProfessionalController@activateProfessional')->name('activateProfessional');
            Route::get('/detail/{id}', 'ProfessionalController@show')->name('professionalDetail');
            Route::get('/deleted', 'ProfessionalController@deletedProfessionals')->name('deletedProfessionals');

            Route::post('/search', 'ProfessionalController@search')->name('searchProfessionals');
            Route::post('/export', 'ProfessionalController@exportProfessionalAsPDF')->name('exportProfessionals');

        });
    });

    Route::group(['middleware' => ['web', 'permission:manage-cmspages']], function () {
        Route::group(['prefix' => 'cms-pages'], function () {
            Route::get('/', 'CmsPagesController@index')->name('listPages');
            Route::get('/edit/{id}', 'CmsPagesController@edit')->name('editPage');
            Route::post('/update', 'CmsPagesController@update')->name('updatePage');
        });

    });

    Route::group(['middleware' => ['web', 'permission:manage-orders']], function () {
        Route::group(['prefix' => 'order'], function () {
            Route::get('/', 'OrderController@index')->name('listOrders');
            Route::get('/deleted', 'OrderController@deleted')->name('listDeletedOrders');
            Route::get('/edit/{id}', 'OrderController@edit')->name('editOrder');
            Route::post('/update', 'OrderController@update')->name('updateOrder');
            Route::get('/orderDetail/{id}', 'OrderController@show')->name('orderDetail');
            Route::post('/delOrder', 'OrderController@delOrder')->name('delOrder');

            Route::post('/search', 'OrderController@filter')->name('searchOrders');
            Route::post('/export', 'OrderController@exportOrderAsPDF')->name('exportOrders');
            Route::post('/exportOrder', 'OrderController@exportOrder')->name('exportOrder');

            Route::get('/filterProfessional', 'OrderController@showProfessionalMaxOrders')->name('showProfessionalMaxOrders');
            Route::get('/professionalExportPdf', 'OrderController@exportPdfProfessional')->name('exportPdfProfessional');
            Route::get('/filterConsumer', 'OrderController@showConsumerMaxOrders')->name('showConsumerMaxOrders');
            Route::get('/consumerExportPdf', 'OrderController@exportPdfConsumer')->name('exportPdfConsumer');
        });

    });

    Route::group(['middleware' => ['web', 'permission:manage-admin-users']], function () {
        Route::group(['prefix' => 'admin-users'], function () {
            Route::get('/', 'AdminController@listAdmins')->name('listAdmins');
            Route::get('/create', 'AdminController@createAdmin')->name('createAdminUser');
            Route::post('/store', 'AdminController@storeAdmin')->name('storeAdminUser');
            Route::get('/edit/{id}', 'AdminController@editAdmin')->name('editAdminUser');
            Route::post('/update', 'AdminController@updateAdmin')->name('udpateAdminUser');
            Route::post('/deactivate', 'AdminController@delet')->name('deactivateAdminUser');
        });

    });


    Route::post('/getSubCategories', 'SubCategoryController@getSubCategories')->name('getSubCategories');
    Route::post('/getCategories', 'CategoryController@getCategories')->name('getCategories');
    Route::post('/getSubChildCategories', 'SubChildCategoryController@getSubChildCategories')->name('getSubChildCategories');

    });


});



Route::get('/reset-success', function () {
    return view('success');
});
// route for mobile user reset password
Route::get('/reset-passsword/{id}', 'API\AuthenticationController@resetPassword')->name('restPasswordLink');
Route::post('/change-password', 'API\AuthenticationController@changePassword')->name('resetPassword');

Route::post('/consumer/reset/', 'API\ResetPasswordController@reset')->name('cosumerResetPasswordSubmit');
Route::post('/professional/reset/', 'API\ProfessionalResetPasswordController@reset')->name('professionalResetPasswordSubmit');
Route::get('/consumer/reset/{token}', 'API\ResetPasswordController@showResetForm')->name('consumerResetPasswordLink');
Route::get('/professional/reset/{token}', 'API\ProfessionalResetPasswordController@showResetForm')->name('professionalResetPasswordLink');
