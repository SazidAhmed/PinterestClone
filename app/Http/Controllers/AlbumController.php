<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Http\Resources\AlbumResource;
use App\Album;
use App\Image;
class AlbumController extends Controller
{
    //album list
    public function getAlbums(){
        $albums = Album::with('category')->where('user_id',auth()->user()->id)->get();
        return $albums;
    }
    
    public function index(){

        return view('album.index');
    }

	public function create(){

		return view('album.create');
	}

    public function store(Request $request){

        $this->validate($request,[
            'name'=>'required|min:3|max:15',
            'description'=>'required|min:3|max:200',
            'category_id'=>'required',
            'image'=>'required|mimes:jpeg,jpg,png'

        ]);
        $imageName = $request->image->hashName();
        $request->image->move(public_path('album'),$imageName);
      
        $album = Album::create([
            'name'=>$request->name,
            'description'=>$request->description,
            'category_id'=> $request->category_id,
            'slug'=>Str::slug($request->name),
            'user_id'=>auth()->user()->id,
            'image'=>$imageName
      ]);
    //   last created album id
       $id = $album->id;
       return response()->json(['id'=>$id]);

    }
    //single album for edit
    public function getOneAlbum($id){
        return Album::with('category')->find($id);
    }

    public function update($id,Request $request){
        $findAlbum = Album::find($id);
        $photo = $findAlbum->image;
        if($request->hasFile('image')){
            $file = $request->file('image');
            $photo = $file->hashName();
            $file->move('./album/',$photo);
        }
        $album = Album::find($id);
        $album->name = $request->name;
        $album->description = $request->description;
        $album->category_id = $request->category_id;
        $album->image = $photo;
       
        $success =  $album->save();
        if($success){
            return response()->json($this->getAlbums());
        }
    }

    public function destroy($id){
        $album = Album::find($id);
        // unlink(public_path('/album/'.$album->image));
        $album->delete();
        return response()->json($this->getAlbums());
        // if($album){
        //     $image = Image::where('album_id',$id)->get();
        //     foreach($image as $img){
        //      unlink(public_path('/images/'.$img->image));
  
        //     }
        //     Image::where('album_id',$id)->delete();
        // }
    }

    public function uploadImages(){
        return view('image.upload');
    }

  
}

