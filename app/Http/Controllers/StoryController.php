<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Story;


class StoryController extends Controller
{
    public function createStory(Request $request)
    {
        $request->validate([
            'content'=>'required|mimes:mp4,ogx,oga,ogv,ogg,webm,jpg,png,jpeg'
        ]);

        $file = $request->file('content');
        $file -> move('upload',$file->getClientOriginalNames());
        $file_name = $file->getClientOriginalNames();

        $insert = new Story();
        $insert -> content = $file_name;
        $insert -> save();

        $data = [
            'content' => $request->content
        ];

        // if (! $token = auth()->attempt($data)) {
        //     return response()->json(['error' => 'Unauthorized'], 401);
        // }

        return response()->json([
            'message' => 'Up story successfully',
            'URL' => $file_name

        ]);
    }
}
