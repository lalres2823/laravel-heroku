<?php

namespace App\Http\Controllers;

use App\Post;
use Illuminate\Http\Request;
use Intervention\Image\Facades\Image;

class PostsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $users = auth()->user()->following()->pluck('profiles.user_id');

        $posts = Post::whereIn('user_id', $users)->with('user')->latest()->paginate(5);

        return view('posts.index', compact('posts'));
    }

    public function create()
    {
        return view('posts.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'caption' => 'required',
            'image' => 'required',

        ]);

        $caption = $request->input('caption');
        // $imagePath = request('image')->store('uploads', 'public');
        
        $image = new Image();
        $image = base64_encode(file_get_contents($request->image));
        // $image = Image::make(public_path("storage/{$imagePath}"))->fit(1200, 1200);
        // $image->save();
        $comment = $request->input('comment');

        auth()->user()->posts()->create(["caption" => $caption, "image" => $image,]); // データベーステーブルbbsに投稿内容を入れる



        // $data = request()->validate([
        //     'caption' => 'required',
        //     'image' => ['required', 'image'],
        // ]);

        // $imagePath = request('image')->store('uploads', 'public');

        // $image = Image::make(public_path("storage/{$imagePath}"))->fit(1200, 1200);
        // $image->save();

        // auth()->user()->posts()->create([
        //     'caption' => $data['caption'],
        //     'image' => $imagePath,
        // ]);

        return redirect('/profile/' . auth()->user()->id);
    }

    public function show(\App\Post $post)
    {
        return view('posts.show', compact('post'));
    }
}
