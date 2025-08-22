<?php

namespace App\Http\Controllers;
use App\Models\Post;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Ramsey\Uuid\Type\Time;
    

class AdminController extends Controller
{
    public  function addpost(){
        return view('admin.add_post');
    }
    public function createpost(Request $request,)
{
    // Validasi input
    $request->validate([
        'title'       => 'required|string|max:255',
        'description' => 'required|string',
        'image'       => 'required|image|mimes:jpg,jpeg,png|max:2048',
    ]);

    // Cek file
    if ($request->hasFile('image')) {
        $image = $request->file('image');
        $imagename = time() . '.' . $image->getClientOriginalExtension();
        $image->move(public_path('img'), $imagename);

        // Simpan ke database
        $post = new Post();
        $post->title = $request->title;
        $post->description = $request->description;
        $post->image = $imagename;
        $post->user_name = Auth::user()->name;
        $post->user_id = Auth::user()->id;
        $post->save();

        return redirect()->back()->with('success', 'Post berhasil ditambahkan');
    }

    return back()->with('error', 'Gagal upload gambar');
    }
    public function allpost(){
        $post = Post::all();
        return view('admin.allpost', compact('post'));
    }
public function updatepost($id){
        $post=post::findOrFail($id);
        return view('admin.updatepost',compact('post'));
    }
    public function postupdate(Request $request,$id){
        
    $request->validate([
        'title'       => 'required|string|max:255',
        'description' => 'required|string|min:10',
        'image'       => 'required|image|mimes:jpg,jpeg,png|max:2048',
    ]);

    $post=post::findOrFail($id);
    $post->title = $request->title;
    $post->description = $request->description;

    if ($request->hasFile('image')) {
        $image = $request->file('image');
        $imagename = time().'.'.$image->getClientOriginalExtension();
        $image->move(public_path('img'), $imagename);
        $post->image = $imagename;
    }
    if ($image=$request->image) {
        $imagename = time().'.'.$image->getClientOriginalExtension();
        $post->image = $imagename;
    }
        $post->user_name = Auth::user()->name;
        $post->user_id   = Auth::user()->id;
        $post->save();
        return redirect()->route('admin.allpost')->with('success', 'Updated Successfully!');
}
    public function deletePost($id){

        $post = Post::findOrFail($id);
        $post->delete();
        return redirect()->route('admin.allpost')->with('status', 'deleted Successfully!');
    }
   
}