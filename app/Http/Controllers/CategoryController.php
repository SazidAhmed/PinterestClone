<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Category;
class CategoryController extends Controller
{
    public function getCategories()
    {
    	$categories = Category::latest()->get();
    	
    	return $categories;
    }

    public function index(){

        return view('category.index');
    }


    public function store(Request $request){

        $this->validate($request,[
            'name'=>'required|min:3|max:15',
        ]);

        $success = Category::create([
            'name'=>$request->name,
        ]);
        if($success){
            return response()->json($this->getCategories());
        }
    }
    
    //single album for edit
    public function getOneCategory($id){
        return Category::find($id);
    }

    public function update(Request $request, $id)
    {
        $this->validate($request,[
            'name'=>'required|min:3|max:15',
        ]);
        
        $category = Category::find($id);
        $category->name = $request->input('name');
       
        $success =  $category->save();
        if($success){
            return response()->json($this->getCategories());
        }
    }

    public function destroy($id){
        $category = Category::find($id);
        $success = $category->delete();
        if($success){
            return response()->json($this->getCategories());
        }
    }
}
