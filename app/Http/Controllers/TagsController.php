<?php

namespace App\Http\Controllers;

use App\Article;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TagsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    private function tagsQuery($needle=null)
    {
        $q = DB::table('articles')->select('id', 'tag');

        if($needle) {
            $q->where('tag', 'LIKE', $needle.'%');
        } else {
            $q->where('tag', 'NOT LIKE', '%.%');
        }

        return $q->orderBy('tag')->get();
    }

    public function index()
    {
        $tags = $this->tagsQuery();

        return response()->json([
            'html' => view('tags-list', ['tags' => $tags])->render()
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
