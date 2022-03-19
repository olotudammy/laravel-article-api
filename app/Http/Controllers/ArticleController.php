<?php

namespace App\Http\Controllers;

use App\Jobs\SendEmailJob;
use App\Models\Article;
use App\Http\Requests\StoreArticleRequest;
use App\Http\Requests\UpdateArticleRequest;
use App\Models\User;
use App\Notifications\ArticleCreatedNotification;
use App\Notifications\ArticleNotification;
use App\Traits\ApiResponder;
use Illuminate\Http\Request;

class ArticleController extends Controller
{

    use ApiResponder;

    /**
     * This gets the login user articles only
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        try {
            $articles = Article::query()
                ->with("user")
                ->where("user_id", auth()->id())
                ->paginate(20, ["*"], "page", 1);
            return $this->success($articles);
        } catch (\Exception $exception) {
            logger($exception);
            return $this->failed("Error occurred123");
        }

    }

    public function store(StoreArticleRequest $request)
    {
        try {
            $user = $request->user();
            $data = $request->validated();
            $data["user_id"] = $user->id; //gets logged in user id
            $article = Article::create($data);
            if ($article) {
                $user->notify(new ArticleCreatedNotification($user, $article));
            }
            return $this->success($article, "Article created");
        } catch (\Exception $exception) {
            logger($exception);
            return $this->failed("Error occurred");
        }
    }


    public function show(Article $article)
    {
        try {
            if (empty($article)) {
                return $this->notFound("Record not found with id {$article->id}");
            }
            return $this->success($article, "Success");
        } catch (\Exception $exception) {
            logger($exception);
            return $this->failed("An error occurred");
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
                return $this->notFound("Record not found with id $id", []);
            }

            $article->update($data);
            return $this->success($article, "Article updated");
        } catch (\Exception $exception) {
            logger($exception);
            return $this->failed("An error occurred");
        }
    }


    public function destroy($id)
    {
        try {
            $article = Article::where("id", $id)->first();
            if (empty($article)) {
                return $this->notFound("Record not found with id $id", []);
            }
            $article->delete();
            return $this->success("Article Deleted");
        } catch (\Exception $exception) {
            logger($exception);
            return $this->failed("An error occurred");
        }
    }

    public function sendEmail()
    {
        try {
            $users = User::all();
            foreach ($users as $user) {
                //$user->notify(new ArticleNotification());
                dispatch(new SendEmailJob($user))->onQueue("high");
            }
            return $this->success([], "Email processing");
        } catch (\Exception $exception) {
            logger($exception);
            return $this->failed("An error occurred");
        }
    }


}
