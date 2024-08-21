<?php

namespace App\Http\Controllers;

use App\Events\TaskUpdated;
use App\Http\Resources\TaskResource;
use App\Jobs\criticalJob;
use App\Models\Task;
use Illuminate\Http\Request;

/**
 * @OA\Info(
 *     title="Task Management API",
 *     version="1.0.0",
 *     description="This is a simple Task Management API built with Laravel",
 *     @OA\Contact(
 *         email="support@example.com"
 *     ),
 *     @OA\License(
 *         name="Apache 2.0",
 *         url="http://www.apache.org/licenses/LICENSE-2.0.html"
 *     )
 * )
 */



class TaskController extends Controller
{


    /**
     * @OA\Get(
     *     path="/api/task",
     *     summary="Get a list of tasks",
     *     tags={"Tasks"},
     *     @OA\Response(
     *         response=200,
     *         description="A list of tasks",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/Task")
     *         )
     *     )
     * )
     */
    public function index()
    {
        return TaskResource::collection(Task::all());
    }

    /**
     * @OA\Post(
     *     path="/api/task",
     *     summary="Create a new task",
     *     tags={"Tasks"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="title", type="string", example="New Task"),
     *             @OA\Property(property="desc", type="string", example="Task description"),
     *             @OA\Property(property="date", type="string", format="date", example="2023-08-21"),
     *             @OA\Property(property="priority", type="string", example="high"),
     *             @OA\Property(property="status", type="string", example="toDo")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Task created successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="New task with id1 was successfully created"),
     *             @OA\Property(property="task", ref="#/components/schemas/Task")
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation failed"
     *     )
     * )
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|min:5|max:255',
            'desc' => 'nullable',
            'date' => 'nullable|date',
            'priority' => 'required|string',
            'status' => 'in:toDo,inProgress,done',
        ]);


        $newTask = Task::create($validated);

        if($validated['priority'] === 'high')
            dispatch((new criticalJob($validated['title'])))->onQueue('critical');
        else
            dispatch((new criticalJob($validated['title'])))->onQueue('default');


        return response()->json([
            'message' => 'New task with id '. $newTask->id .' was successfully created',
            'task' => $newTask
        ], 201);
    }

    /**
     * @OA\Get(
     *     path="/api/task/{id}",
     *     summary="Get a specific task",
     *     tags={"Tasks"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Task ID",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Task retrieved successfully",
     *         @OA\JsonContent(ref="#/components/schemas/Task")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Task not found"
     *     )
     * )
     */
    public function show(string $id)
    {
        return new TaskResource(Task::findOrFail($id));
    }

    /**
     * @OA\Put(
     *     path="/api/task/{id}",
     *     summary="Update a specific task",
     *     tags={"Tasks"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Task ID",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="title", type="string", example="Updated Task"),
     *             @OA\Property(property="desc", type="string", example="Updated description"),
     *             @OA\Property(property="date", type="string", format="date", example="2023-08-22"),
     *             @OA\Property(property="priority", type="string", example="medium"),
     *             @OA\Property(property="status", type="string", example="inProgress")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Task updated successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Task number 1 updated successfully")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Task not found"
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation failed"
     *     )
     * )
     */
    public function update(Request $request, string $id)
    {
        $validated = $request->validate([
            'title' => 'required|min:5|max:255',
            'desc' => 'nullable',
            'date' => 'nullable|date',
            'priority' => 'required|string',
            'status' => 'in:toDo,inProgress,done',
        ]);

        Task::find($id)->update($validated);

        if($validated['priority'] === 'high')
            dispatch((new criticalJob($validated['title'])))->onQueue('critical');
        else
            dispatch((new criticalJob($validated['title'])))->onQueue('default');


        return response()->json([
            'message' => 'Task number '. $id .' updated successfully',
        ], 200);
    }

    /**
     * @OA\Delete(
     *     path="/api/task/{id}",
     *     summary="Delete a specific task",
     *     tags={"Tasks"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Task ID",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Task deleted successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Task number 1 deleted successfully")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Task not found"
     *     )
     * )
     */
    public function destroy(string $id)
    {
       Task::find($id)->delete();

        event(new TaskUpdated('reRender'));

       return response()->json([
           'message' => 'Task number '. $id .' deleted successfully',
       ], 200);
    }
}
