<?php

namespace App\Http\Controllers\Back;

use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Models\Tag;
use App\Http\Requests\PostRequest;
// use Illuminate\Http\Request;



class PostController extends Controller
{
    /**
     * 一覧画面
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function index()
    {
        $posts = Post::with('user')->latest('id')->paginate(20);
        return view('back.posts.index', compact('posts'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $tags = Tag::pluck('name', 'id')->toArray();
        return view('back.posts.create', compact('tags'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(PostRequest $request)
    {
        $post = Post::create($request->all());
        $post->tags()->attach($request->tags());
        if($post){
            return redirect()
                ->route('back.posts.edit', $post)
                ->withSuccess('データを登録しました。');
        }else{
            return redirect()
                ->route('back.posts.create')
                ->withError('データの登録に失敗しました。');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Post $post)
    {
        $tags = Tag::pluck('name', 'id')->toArray();
        return view('back.posts.edit', compact('post', 'tags'));
        // return view('viewフォルダの階層', compact('変数名'));
        //                                     ↑compact関数でviewに変数を渡す。
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(PostRequest $request, Post $post)
    {
        $post->tags()->sync($request->tags());

        if ($post->update($request->all())){
            $flash = ['success' => 'データを更新しました。'];
        }else{
            $flash = ['error' => 'データの更新に失敗しました'];
        }

        return redirect()
            ->route('back.posts.edit', $post)
            ->with($flash);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Post $post)
    {
        $post->tags()->detach();

        if ($post->delete()) {
            $flash = ['success' => 'データを削除しました。'];
        } else {
            $flash = ['error' => 'データの削除に失敗しました'];
        }
    
        return redirect()
            ->route('back.posts.index')
            ->with($flash);
    }
}
