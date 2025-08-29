<?php


namespace App\Custom;


use Modules\Blog\Entities\Post;
use Modules\Core\Entities\Menu\Module;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Modules\BlogType\Entities\PostRelation;


class PsJoinTable extends Controller
{

    public function disableModules()
    {
        $disableModules = Module::allDisabled();
        $module = "";
        foreach ($disableModules as $disableModule) {
            $module .= strtolower($disableModule);
        }

        return $module;
    }

    public function posts()
    {

        $posts = PostRelation::with(['language', 'postType', 'category'])
            ->join('posts', 'post_relations.post_id', '=', 'posts.id')
            ->latest('posts.id')->get();

        return $posts;
    }

    //    public function test($table,$foreginId) {
    //
    //        $posts = Post::with(['category','postType'])
    //            ->join('post_relations', 'posts.id', '=', 'post_relations.post_id')
    ////            ->select('posts.id', 'posts.title', 'post_relations.post_type_id')
    //            ->get();
    ////        $posts = Post::with(['category','postType'])->latest("id")->get();
    //
    //        return response($posts,200);
    //
    //        $posts = DB::table('posts')
    //            ->join($table, 'posts.id', '=', $table.'.'.$foreginId)
    ////            ->select('posts.id', 'posts.title', 'post_relations.post_type_id')
    //            ->get();
    //
    ////        $posts = Post::with(['category','postType'])->latest("id")->get();
    //
    //        return response($posts,200);
    //
    //    }

    public function postRelationTable()
    {

        //        $posts = DB::table('posts')
        //            ->join('post_relations', 'posts.id', '=', 'post_relations.post_id')
        //            ->select('posts.id', 'posts.title', 'post_relations.post_type_id')
        //            ->get();
        ////        $posts = Post::with(['category','postType'])->latest("id")->get();

        //        return response($posts,200);

    }
}
