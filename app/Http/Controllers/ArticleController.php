<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Http\Requests\StoreArticleRequest;
use App\Http\Requests\UpdateArticleRequest;
use App\Notifications\ArticleCreatedNotification;
use Illuminate\Http\Request;

class ArticleController extends Controller
{

    /**
     * This gets the login user articles only
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        try {
            $user = $request->user(); //login user
            //$articles = Article::where("user_id", $user->id)->get(); //this fetches all the article from the database
            $articles = $user->articles;
            return response()->json($articles);
        }catch (\Exception $exception) {
            logger($exception);
            return response()->json("An error occurred", 400);
        }

    }

    public function store(StoreArticleRequest $request)
    {
        try {
            $data = $request->validated();
            $user = $request->user();
            $data["user_id"] = $user->id; //gets logged in user id
            $article = Article::create($data);
            if ($article) {
                $user->notify(new ArticleCreatedNotification($user, $article));
            }
            return response()->json($article);
        }catch (\Exception $exception) {
            logger($exception);
           return response()->json("An error occurred", 400);
        }
    }


    public function show($id)
    {
        try {
            $article = Article::where("id", $id)->first();
            if (empty($article)) {
                return response()->json("Record not found with id $id", 400);
            }
            return response()->json($article);
        }catch (\Exception $exception) {
            logger($exception);
            return response()->json("An error occurred", 400);
        }
    }


    public function update(Request $request, $id)
    {
        $data = $request->validate([
            "title" => "required",
            "body" => "required",
            "published_at" => "required"
        ]);

        try {
            $article = Article::where("id", $id)->first();
            if (empty($article)) {
                return response()->json("Record not found with id $id", 400);
            }

            $article->update($data);

            return response()->json($article);
        }catch (\Exception $exception) {
            logger($exception);
            return response()->json("An error occurred", 400);
        }
    }


    public function destroy($id)
    {
        try {
            $article = Article::where("id", $id)->first();
            if (empty($article)) {
                return response()->json("Record not found with id $id", 400);
            }
            $article->delete();
            return response()->json("Article Deleted");
        }catch (\Exception $exception) {
            logger($exception);
            return response()->json("An error occurred", 400);
        }
    }
}
