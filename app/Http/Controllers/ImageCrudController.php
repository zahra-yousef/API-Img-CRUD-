<?php

namespace App\Http\Controllers;

use App\Models\ImageCrud;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class ImageCrudController extends Controller
{
    public function create(Request $request)
    {
        $images=new ImageCrud();
        $request->validate([
            'title'=>'required',
            'image'=>'required|max:1024'
        ]);

        $filename="";
        if($request->hasFile('image')){
            $file = $request->file('image') ;
            $extention = $file->getClientoriginalExtension();
            $filename = time().'.'.$extention;
            $file->move('posts/',$filename);
        }else{
            $filename=Null;
        }

        $images->title=$request->title;
        $images->image=$filename;
        $result=$images->save();
        $result = 1;
        if($result){
            return response()->json(['success'=>true]);
        }else{
            return response()->json(['success'=>false]);
        }
    }

    public function get()
    {
        $images=ImageCrud::orderBy('id','DESC')->get();
        return response()->json($images);
    }

    public function edit($id){
        $images=ImageCrud::findOrFail($id);
        return response()->json($images);
    }

    public function update(Request $request, $id){
        $images=ImageCrud::findOrFail($id);
        $destination=public_path("posts/".$images->image);
        $filename="";
        if($request->hasFile('image')){
            if(File::exists($destination)){
                File::delete($destination);
            }
            
            $file = $request->file('image') ;
            $extention = $file->getClientoriginalExtension();
            $filename = time().'.'.$extention;
            $file->move('posts/',$filename);
        }else{
            $filename=$request->image;
        }
        
        $images->title=$request->title;
        $images->image=$filename;
        $result=$images->save();
        if($result){
            return response()->json(['success'=>true]);
        }else{
            return response()->json(['success'=>false]);
        }

        return response()->json($images);
    }

    public function delete($id){
        $image=ImageCrud::findOrFail($id);
        $destination=public_path("posts/".$image->image);
        if(File::exists($destination)){
            File::delete($destination);
        }
        $result=$image->delete();
        if($result){
            return response()->json(['success'=>true]);
        }else{
            return response()->json(['success'=>false]);
        }
    }
}