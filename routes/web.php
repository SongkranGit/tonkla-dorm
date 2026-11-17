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

Auth::routes();

Route::group(['prefix' => '/'], function () {
    Route::get('/logout','Auth\LoginController@logout');
    Route::get('/', 'HomeController@index')->name('home');
    Route::get('/home', 'HomeController@index')->name('home');
    Route::get('/branch/{id}', 'HomeController@selectBranch')->name('branch');
    Route::get('/allbranch', 'HomeController@showAllBranch');
    Route::resource('/equipment', 'EquipmentController');
 
    Route::post('/room/move','RoomController@move');
    Route::get('/room/print/{id}','RoomController@showPrint')->name('print');
    Route::get('/room/printcheckout/{id}','RoomController@showCheckoutPrint')->name('printcheckout');
    Route::get('/room/printcheckin/{id}','RoomController@showPrintCheckin')->name('printcheckin');
    Route::post('/room/print','RoomController@updatePrint');
    Route::resource('/room', 'RoomController', ['only' => [
        'index', 
        'edit',
        'update',
    ]]);

    Route::get('/room/generate-receipt-number','RoomController@generateReceiptNumber')->name('room.generate-receipt-number');
});

Route::group(['prefix' => '/admin'], function () { 
    Route::get('/roomhistory/print/{id}','Admin\RoomHistoryController@showPrint');
    Route::get('/roomhistory/printcheckout/{id}','Admin\RoomHistoryController@showPrintCheckout');
    Route::get('/roomhistory/printcheckin/{id}','Admin\RoomHistoryController@showPrintCheckin');
    Route::resource('/roomhistory', 'Admin\RoomHistoryController', ['except' => [
        'create', 
        'store', 
    ]]);
    Route::resource('/equipmenthistory', 'Admin\EquipmentHistoryController', ['except' => [
        'create',
        'store',
    ]]); 
    Route::group(['prefix' => '/reports'], function () { 
        Route::get('/monthlyreport','Admin\ReportController@getMonthlyReport');
        Route::post('/monthlyreport/excel','Admin\ReportController@exportMonthlyExcelReport');
    });

    // Approve
    Route::resource('/approval', 'Admin\RoomApprovalController');
    Route::post('/approval/approve/{id}', 'Admin\RoomApprovalController@approve');
    Route::post('/approval/reset-status/{id}', 'Admin\RoomApprovalController@resetStatus');
    Route::post('/approval/request-for-approve', 'Admin\RoomApprovalController@requestForApprove')->name('approval.request_for_approve');
    Route::resource('/privilege', 'Admin\ApprovalPrivilegeController');
    Route::post('/privilege/approve/{id}', 'Admin\ApprovalPrivilegeController@approve');

    Route::group(['prefix' => '/settings'], function () { 
        //ElectricSettingController
        Route::get('/electric/edit','Admin\Setting\ElectricSettingController@editElectric');
        Route::get('/electric','Admin\Setting\ElectricSettingController@getElectric');
        Route::post('/electric','Admin\Setting\ElectricSettingController@storeElectric');
        //WaterSettingController
        Route::get('/water/edit','Admin\Setting\WaterSettingController@editWater');
        Route::get('/water','Admin\Setting\WaterSettingController@getWater');
        Route::post('/water','Admin\Setting\WaterSettingController@storeWater');
        //RoomRateDailySettingController
        Route::get('/roomratedaily/edit','Admin\Setting\RoomRateDailySettingController@editRoomRateDaily');
        Route::get('/roomratedaily','Admin\Setting\RoomRateDailySettingController@getRoomRateDaily');
        Route::post('/roomratedaily','Admin\Setting\RoomRateDailySettingController@storeRoomRateDaily');

        Route::get('/roomratedaily2/edit','Admin\Setting\RoomRateDailySettingController@editRoomRateDaily2');
        Route::get('/roomratedaily2','Admin\Setting\RoomRateDailySettingController@getRoomRateDaily2');
        Route::post('/roomratedaily2','Admin\Setting\RoomRateDailySettingController@storeRoomRateDaily2');

        Route::get('/roomratedaily3/edit','Admin\Setting\RoomRateDailySettingController@editRoomRateDaily3');
        Route::get('/roomratedaily3','Admin\Setting\RoomRateDailySettingController@getRoomRateDaily3');
        Route::post('/roomratedaily3','Admin\Setting\RoomRateDailySettingController@storeRoomRateDaily3');

        //RoomRateMonthlySettingController
        Route::get('/roomratemonthlyfan/edit','Admin\Setting\RoomRateMonthlySettingController@editRoomRateMonthlyFan');
        Route::get('/roomratemonthlyfan','Admin\Setting\RoomRateMonthlySettingController@getRoomRateMonthlyFan');
        Route::post('/roomratemonthlyfan','Admin\Setting\RoomRateMonthlySettingController@storeRoomRateMonthlyFan');
        
        Route::get('/roomratemonthlyfan2/edit','Admin\Setting\RoomRateMonthlySettingController@editRoomRateMonthlyFan2');
        Route::get('/roomratemonthlyfan2','Admin\Setting\RoomRateMonthlySettingController@getRoomRateMonthlyFan2');
        Route::post('/roomratemonthlyfan2','Admin\Setting\RoomRateMonthlySettingController@storeRoomRateMonthlyFan2');
 
        Route::get('/roomratemonthlyair/edit','Admin\Setting\RoomRateMonthlySettingController@editRoomRateMonthlyAir');
        Route::get('/roomratemonthlyair','Admin\Setting\RoomRateMonthlySettingController@getRoomRateMonthlyAir');
        Route::post('/roomratemonthlyair','Admin\Setting\RoomRateMonthlySettingController@storeRoomRateMonthlyAir');
        
        Route::get('/roomratemonthlyair2/edit','Admin\Setting\RoomRateMonthlySettingController@editRoomRateMonthlyAir2');
        Route::get('/roomratemonthlyair2','Admin\Setting\RoomRateMonthlySettingController@getRoomRateMonthlyAir2');
        Route::post('/roomratemonthlyair2','Admin\Setting\RoomRateMonthlySettingController@storeRoomRateMonthlyAir2');
        
 
        Route::get('/roomratemonthlyhouse/edit','Admin\Setting\RoomRateMonthlySettingController@editRoomRateMonthlyHouse');
        Route::get('/roomratemonthlyhouse','Admin\Setting\RoomRateMonthlySettingController@getRoomRateMonthlyHouse');
        Route::post('/roomratemonthlyhouse','Admin\Setting\RoomRateMonthlySettingController@storeRoomRateMonthlyHouse');
        
        //ExtraSettingController 
        Route::get('/internet','Admin\Setting\ExtraSettingController@');
        Route::resource('/extra', 'Admin\Setting\ExtraSettingController');

        //VATSettingController
        Route::get('/vat/edit','Admin\Setting\VATSettingController@editVAT');
        Route::get('/vat','Admin\Setting\VATSettingController@getVAT');
        Route::post('/vat','Admin\Setting\VATSettingController@storeVAT');
        
        Route::get('/vatspecial/edit','Admin\Setting\VATSettingController@editVATSpecial');
        Route::get('/vatspecial','Admin\Setting\VATSettingController@getVATSpecial');
        Route::post('/vatspecial','Admin\Setting\VATSettingController@storeVATSpecial');
        
        Route::get('/vatspecialproperty/edit','Admin\Setting\VATSettingController@editVATSpecialProperty');
        Route::get('/vatspecialproperty','Admin\Setting\VATSettingController@getVATSpecialProperty');
        Route::post('/vatspecialproperty','Admin\Setting\VATSettingController@storeVATSpecialProperty');
    });


});

Route::group(['prefix' => '/equipment'], function(){
        Route::get('/equipment', 'EquipmentController@create');
        Route::get('/equipemnt/edit', 'EquipmentController@edit');
        Route::post('/equipment', 'EquipmentController@store'); 
});