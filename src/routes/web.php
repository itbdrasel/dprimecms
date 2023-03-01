<?php


use App\Http\Controllers\Frontend\HomeController;

Route::get('all/clear',function(){
    Artisan::call('config:clear');
    Artisan::call('config:cache');
    Artisan::call('route:clear');
    Artisan::call('route:cache');
    Artisan::call('view:clear');
    Artisan::call('view:cache');
    Artisan::call('event:clear');
    Artisan::call('event:cache');
    Artisan::call('cache:clear');
    Artisan::call('optimize');
    Artisan::call('optimize:clear');
    return "Cleared";
});


Route::get('{slag}',[HomeController::class,'index']);
Route::get('{slag}/{id}',[HomeController::class,'index']);
