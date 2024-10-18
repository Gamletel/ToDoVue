<?php

namespace App\Http\Controllers;

use App\Http\Resources\TodoCollection;
use App\Models\ToDo;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ToDoController extends Controller
{
    /**
     * @return JsonResponse
     */
    public function index()
    {
        $to_dos = ToDo::orderBy('id', 'ASC')->paginate(6);

        return response()->json($to_dos);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
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

    /**
     * @param Request $request
     * @param $id
     * @return JsonResponse
     */
    public function update(Request $request, $id)
    {
        $todo = ToDo::findOrFail($id);

        $data = $request->validate([
            'title'=>'nullable|string',
            'subtitle'=>'nullable|string',
            'isCompleted'=>'nullable|boolean',
        ]);

        $todo->update($data);

        return response()->json([
            'todo'=>$todo,
            'message'=>'ToDo is successfully updated!',
        ]);
    }

    /**
     * @param Request $request
     * @param $id
     * @return JsonResponse
     */
    public function updateIsCompleted(Request $request, $id)
    {
        $todo = ToDo::findOrFail($id);

        $isCompleted = $request->validate(['isCompleted'=>'required|boolean']);

        $todo->isCompleted = $isCompleted;
        $todo->save();

        return response()->json([
            'todo'=>$todo,
            'message'=>'ToDo is successfully updated!',
        ]);
    }

    /**
     * @param $id
     * @return JsonResponse
     */
    public function destroy($id)
    {
        $todo = ToDo::findOrFail($id);
        $todo->delete();

        return response()->json([
            'message'=>'Задача успешно удалена!'
        ]);
    }
}
