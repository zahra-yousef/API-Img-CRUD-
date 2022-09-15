<?php

namespace App\Http\Controllers;

use App\Models\ImageCrud;
use Illuminate\Http\Request;

class ImageController extends Controller
{
    public function index()
    {
        $imgs = ImageCrud::all();
        return view('welcome',compact('imgs'));
    }
}
