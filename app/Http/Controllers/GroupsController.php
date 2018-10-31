<?php

namespace App\Http\Controllers;

use App\Article;
use App\Group;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class GroupsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $groups = Group::where('is_main', 1)->get();

        return response()->json([
            'html' => view('tags-list', ['groups' => $groups])->render(),
        ]);
    }

    public function subgroups($groupId)
    {

        $articles = DB::table('articles')
            ->select('articles.id', 'articles.title','articles.sub_group_id', 'groups.name as sub_group_name')
            ->leftJoin('groups', 'articles.sub_group_id', '=', 'groups.id')
            ->where('articles.group_id', $groupId)
            ->orderBy('groups.name')
            ->orderBy('articles.title')
            ->get();


        $groups = [];

        foreach ($articles as $article) {

            if(!isset($groups[$article->sub_group_id])) {
                $groups[$article->sub_group_id] = [
                    'name' => $article->sub_group_name,
                    'articles' => [$article]
                ];
            } else {
                $groups[$article->sub_group_id]['articles'][] = $article;
            }
        }

        $parentGroup = Group::where('id', $groupId)->first();



        return response()->json([
            'html' => view('subgroups-list', [
                'groups' => $groups,
                'parentGroup' => $parentGroup
            ])->render()
        ]);
    }



    /**
     * @param Request $request
     */
    public function find(Request $request)
    {
        $tags = $this->tagsQuery($request->get('needle'));

        return view('tags-list', ['tags' => $tags]);
    }
}
