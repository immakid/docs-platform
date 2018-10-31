<?php

namespace App\Http\Controllers;

use App\Article;
use App\Group;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class ArticlesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {


    }

    public function createOrGetGroup($name, $is_main=null)
    {
        $group = Group::where([
            'name' => $name,
            'is_main' => $is_main
        ])->first();

        if(!$group) {
            $group = Group::firstOrCreate([
                'name' => $name,
                'is_main' => $is_main
            ]);
        }

        return $group;
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $validatedData = $request->validate([
            'title' => 'required|max:255',
            'group' => 'required|max:255',
            'sub_group' => 'required|max:255',
            'content' => 'required',
        ]);

        $group = $this->createOrGetGroup($validatedData['group'], 1);
        $subGroup = $this->createOrGetGroup($validatedData['sub_group']);


        $article = new Article();
        $article->title = $validatedData['title'];
        $article->group_id = $group->id;
        $article->sub_group_id = $subGroup->id;
        $article->content = json_encode($validatedData['content']);
        $article->save();


        return response()->json([
            'article' => $article
        ], 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Article  $article
     * @return \Illuminate\Http\Response
     */
    public function show(Article $article)
    {

        $groups = Group::whereIn('id', [
            $article->group_id,
            $article->sub_group_id])->get();

        $article->group_name = $groups[0]->name;
        $article->sub_group_name = $groups[1]->name;


        $article->content = json_decode($article->content, 1);

        return $article;
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Article  $article
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Article $article)
    {
        $validatedData = $request->validate([
            'tag' => 'required|unique:articles|max:255',
            'content' => 'required',
        ]);


        $article->content = json_encode($validatedData['content']);
        $article->save();


        return response()->json([]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Article  $article
     * @return \Illuminate\Http\Response
     */
    public function destroy(Article $article)
    {
        $article->delete();

        return response()->json([], 204);
    }
}
