<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateTodoRequest;
use App\Http\Requests\UpdateTodoRequest;
use App\Models\Todo;
use Illuminate\Http\Request;

class TodoApiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = $request->search;
        $perPage = $request->has('perPage') ? $request->perPage : 15;
        $todos = Todo::when($search, function ($query, $search) {
            return $query->where('title', 'LIKE', "%{$search}%")
                ->orWhere('description', 'LIKE', "%{$search}%");
        })->paginate($perPage);
        return response()->json($todos);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CreateTodoRequest $request)
    {
        $todos = Todo::create($request->all());
        return response()->json([
            'message' => 'New Task Added Successfully' ,
            'data' => $todos
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $todos = Todo::findOrfail($id);
        return response()->json($todos);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateTodoRequest $request, string $id)
    {
        $todos = Todo::findOrfail($id);
        $todos->fill($request->only(['title','description','completed']));
        $todos->save();
        return response()->json([
            'message' => 'Task Updated Successfully' ,
            'data' => $todos
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $todos = Todo::findOrfail($id);
        $todos->delete();
        return response()->json([
            'message' => 'Task Deleted Successfully'
        ],200);
    }
}
