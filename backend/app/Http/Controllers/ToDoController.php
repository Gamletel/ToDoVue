<?php

namespace App\Http\Controllers;

use App\Http\Resources\TodoCollection;
use App\Models\ToDo;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ToDoController extends Controller
{
    public function index()
    {
        $to_dos = ToDo::orderBy('id', 'DESC')->get();

        return response()->json($to_dos);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title'=>'required|string|min:10|max:128',
            'subtitle'=>'nullable|string|max:256',
        ]);

        $todo = new ToDo;
        $todo->title = $data['title'];
        $todo->subtitle = $data['subtitle'];
        $todo->save();

        return response()->json([
            'message'=>'Todo successfully created',
            'todo'=>$todo,
        ], 200);
    }
}
