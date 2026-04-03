<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PostController extends Controller
{
    public function index(Request $request)
{
    $page = $request->get('page', 1);
    $limit = $request->get('limit', 10);

    $offset = ($page - 1) * $limit;

    $posts = DB::table('posts')
            ->offset($offset)
            ->limit($limit)
            ->get();

    $total = DB::table('posts')->count();

    return response()->json([
        'posts' => $posts,
        'current_page' => (int) $page,
        'total_posts' => $total,
        'has_more' => ($offset + $limit) < $total
    ]);
}

    public function store(Request $request)
    {

    for($i=0; $i<=1000; $i++){
        
     $post = Post::create([
            'title' => $request->title,
            'content' => $request->content
        ]);

        //return response()->json($post);
        echo "inserted";
    }

       
    }
}
