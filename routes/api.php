<?php

use App\Http\Controllers\ArticleController;
use App\Http\Controllers\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::post('/auth/register', [AuthController::class, 'register']);
Route::post('/auth/login', [AuthController::class, 'login']);

Route::group(['middleware' => ['auth:sanctum']], function () {
    Route::get('/me', function(Request $request) {
        return auth()->user();
    });
    Route::post('/auth/logout', [AuthController::class, 'logout']);
});



//ARTICLE ROUTES
Route::get("article", [ArticleController::class, "index"]);
Route::post("store-article", [ArticleController::class, "store"])->middleware("article");
Route::get("article/{article_id}", [ArticleController::class, "show"]);
Route::put("article/{article_id}", [ArticleController::class, "update"]);
Route::delete("article/{article_id}", [ArticleController::class, "destroy"]);

//apiResourceRoute
//Route::apiResource("articles", ArticleController::class);
