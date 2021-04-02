<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// })->name('user');

Route::post('/sanctum/token', 'Api\LoginController@login')->name('login');

Route::group(['middleware'=>[]],function () {
    Route::group(['prefix'=>'user','as'=>'user.','middleware'=>[]],function () {
        Route::get('users', 'Api\UserController@users')->name('users');
    });
});

Route::group(['middleware'=>['auth:sanctum']],function () { 
    Route::group(['prefix'=>'user','as'=>'user.','middleware'=>[]],function () {
        Route::get('', 'Api\UserController@show')->name('show');
    });
    Route::group(['prefix'=>'im','as'=>'im.','middleware'=>[]],function () {
        Route::post('bindUid', 'Api\ImController@bindUid')->name('bindUid');
        Route::post('send', 'Api\ImController@send')->name('send');
        Route::post('joinGroup', 'Api\ImController@joinGroup')->name('joinGroup');
    });
    // Route::group(['prefix'=>'upload','as'=>'upload.','middleware'=>[]],function () {
    //     Route::post('image', 'Api\UploadController@image')->name('image');
    //     Route::post('file', 'Api\UploadController@file')->name('file');
    //     Route::post('audio', 'Api\UploadController@audio')->name('audio');//base64
    //     Route::post('fileAudio', 'Api\UploadController@fileAudio')->name('fileAudio');//文件
    // });

});
Route::group(['middleware'=>[]],function () { 
   
    Route::group(['prefix'=>'im','as'=>'im.','middleware'=>[]],function () {
        Route::post('bindGroupUid', 'Api\ImController@bindGroupUid')->name('bindGroupUid');
        Route::post('groupSend', 'Api\ImController@groupSend')->name('groupSend');
    });
    Route::group(['prefix'=>'upload','as'=>'upload.','middleware'=>[]],function () {
        Route::post('image', 'Api\UploadController@image')->name('image');
        Route::post('file', 'Api\UploadController@file')->name('file');
        Route::post('audio', 'Api\UploadController@audio')->name('audio');//base64
        Route::post('fileAudio', 'Api\UploadController@fileAudio')->name('fileAudio');//文件
    });
   

});


