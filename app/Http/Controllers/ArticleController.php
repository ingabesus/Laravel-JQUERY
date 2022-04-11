<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Type;
use App\Http\Requests\StoreArticleRequest;
use App\Http\Requests\UpdateArticleRequest;
use \Illuminate\Support\Facades\DB;

use Illuminate\Http\Request;
use Validator;

class ArticleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $articles = Article::sortable()->get();
        $types = Type::all();
        return view("article.index", ['articles'=>$articles], ['types'=>$types]);
    }

    public function indexAjax() {

        $articles = Article::with('articleType')->sortable()->get();
        $articles_array = array(
            'articles' => $articles );
        $json_response =response()->json($articles_array); 
    
        return $json_response;
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $articles = Article::all();
        $types = Type::all();
        return view('article.create',
         ['articles' =>$articles,
         'types'=> $types
         ]
        ); 
        
        return view("article.create");
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreArticleRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        
        $input = [
            'article_title'=> $request->article_title,
            'article_description'=> $request->article_description,
            'article_type_id'=> $request->article_type_id,
        ];

        $rules = [
            'article_title'=> 'required|min:3', 
            'article_description'=> 'required',
            'article_type_id'=> 'required',
        ];

        $customMessages = [
            'required' => "This field is required"
        ];

        $validator = Validator::make($input, $rules);

        if($validator->fails()) {

            $errors = $validator->messages()->get('*'); 
            $article_array = array(
                'errorMessage' => "validator fails",
                'errors' => $errors
            );
        } else {

        $article = new Article;
        $article->title = $request->article_title;
        $article->description = $request->article_description;
        $article->type_id = $request->article_type_id;
        $article->save();

        $sort = $request->sort ;
        $direction = $request->direction ;

        $articles = Article::with("articleType")->sortable([$sort => $direction ])->get();

        $article_array = array(
            'successMessage' => "Article stored succesfuly",
            'articleId' => $article->id,
            'articleTitle' => $article->title,
            'articleDescription' => $article->description,
            'articleTypeid' => $article->type_id,
            'articleTypetitle' => $article->articleType->title,
            'articles'=> $articles,
        );
    }
        $json_response =response()->json($article_array); 
       
        return $json_response;
       
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Article  $article
     * @return \Illuminate\Http\Response
     */
    public function show(Article $article)
    {
        $articles=Article::with('articleType')->get();
        $article_array = array(
            'successMessage' => "Article retrieved succesfuly",
            'articleId' => $article->id,
            'articleTitle' => $article->title,
            'articleDescription' => $article->description,
            'articleTypeid' => $article->type_id,
            'articleTypetitle' => $article->articleType->title,
        );

        $json_response =response()->json($article_array); 

        return $json_response;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Article  $article
     * @return \Illuminate\Http\Response
     */
    public function edit(Article $article)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateArticleRequest  $request
     * @param  \App\Models\Article  $article
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Article $article)
    {
      
        $article->title = $request->article_title;
        $article->description = $request->article_description;
        $article->type_id = $request->article_type_id;
        $article->save();

        $article_array = array(
            'successMessage' => "Article stored succesfuly",
            'articleId' => $article->id,
            'articleTitle' => $article->title,
            'articleDescription' => $article->description,
            'articleTypeid' => $article->type_id,
            'articleTypetitle' => $article->articleType->title,
          
        );

        // 
        $json_response =response()->json($article_array); 
       
        return $json_response;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Article  $article
     * @return \Illuminate\Http\Response
     */
    public function destroy(Article $article)
    {
        $article->delete();
        $success_array = array(
            'successMessage' => "Article deleted successfuly". $article->id,
        );
        $json_response =response()->json($success_array);

        return $json_response;
    }

    public function search(Request $request) {

        $searchValue = $request->searchValue;

        $articles = DB::table('articles')
        ->selectraw('types.title as type_title, articles.*')
        ->join('types','types.id','=','articles.type_id')
        ->where('articles.title', 'like', "%{$searchValue}%")
        ->orWhere('articles.description', 'like', "%{$searchValue}%")
        ->orWhere('types.title', 'like', "%{$searchValue}%")
        ->get();

        if(count($articles) > 0) {
            $article_array = array(
                'articles' => $articles
            );
        } else {
            $article_array = array(
                'errorMessage' => 'No articles found'
            );
        }

        $json_response =response()->json($article_array);

        return $json_response;

    }

    public function filter(Request $request) {

        $select_type_id = $request->select_type_id;

        $articles = Article::with('articleType')->get();
        $articles = Article::where('type_id', '=', $select_type_id)->sortable()->get();
        

        if(count($articles) > 0) {
            $article_array = array(
                'articles' => $articles,
                
            );
        } else {
            $article_array = array(
                'errorMessage' => 'No articles found'
            );
        }

        $json_response =response()->json($article_array);

        return $json_response;

    }
}

