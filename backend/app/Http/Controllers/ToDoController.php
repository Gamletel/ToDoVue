<?php

namespace App\Http\Controllers;

use App\Http\Resources\TodoCollection;
use App\Models\ToDo;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ToDoController extends Controller
{

    /**
     * @return TodoCollection
     */
    public function index()
    {
        $to_dos = ToDo::all();

        return new TodoCollection($to_dos);
    }
}
