<?php

use App\Http\Controllers\ArticleController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


//ARTICLE ROUTES
Route::get("article", [ArticleController::class, "index"]);
Route::post("store-article", [ArticleController::class, "store"]);
Route::get("article/{article_id}", [ArticleController::class, "show"]);
Route::put("article/{article_id}", [ArticleController::class, "update"]);
Route::delete("article/{article_id}", [ArticleController::class, "destroy"]);
