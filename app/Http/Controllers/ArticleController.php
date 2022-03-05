<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Http\Requests\StoreArticleRequest;
use App\Http\Requests\UpdateArticleRequest;
use Illuminate\Http\Request;

class ArticleController extends Controller
{

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        try {
            $articles = Article::all(); //this fetches all the article from the database
            return response()->json($articles);
        }catch (\Exception $exception) {
            return response()->json("An error occurred", 400);
        }

    }

    public function store(StoreArticleRequest $request)
    {
        try {
            $data = $request->validated();
            $data["user_id"] = 1;
            $article = Article::create($data);
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
            return response()->json("An error occurred", 400);
        }
    }
}
