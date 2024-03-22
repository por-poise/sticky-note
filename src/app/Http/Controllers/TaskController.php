<?php

namespace App\Http\Controllers;


use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

use App\Models\User;
use App\Models\Category;
use App\Models\Task;

class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if (!Auth::check()) {
            return redirect('login');
        }

        $categories = Category::where(['user_id' => Auth::id() ])
        ->get();
        
        $tasks = Task::where(['user_id' => Auth::id()])
        ->with(['category:id,name'])
        ->get();
        // ->paginate(20);// TODO: ページネーション実装

        return view('tasks.index', [
            'categories' => $categories,
            'tasks' => $tasks
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        if (!Auth::check()) {
            return response()->json([
                'status' => 'error',
                'message' => 'この操作にはログインが必要です。',
            ]);
        }
        Log::debug('begin TaskController.store');

        $task = DB::transaction(function() use ($request) {
            $categoryId = $request->categoryId;
            if ($categoryId <= 0) {
                $category = Category::create([
                    'user_id' => Auth::id(),
                    'name'=> $request->categoryName,
                ]);
                
                $categoryId = $category->id;
                Log::debug('created category: ' . $categoryId);
            }

            $task = Task::create([
                'user_id' => Auth::id(),
                'category_id' => $categoryId,
                'name' => $request->name,
                'due_date'=> $request->dueDate,
                'color' => $request->color,
                'description'=> $request->description,
                'status' => $request->status,
            ]);
            return $task;
        });
        $task->category_name = $task->category->name;
        Log::debug('finish TaskController.store');
        return response()->json([
            'status' => 'ok',
            'task' => $task
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        if (!Auth::check()) {
            return response()->json([
                'status' => 'error',
                'message' => 'この操作にはログインが必要です。',
            ]);
        }

        $task = Task::where(['id' => $id])->get()[0];
        return response()->json([
            'status' => 'ok',
            'task' => $task
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        if (!Auth::check()) {
            return response()->json([
                'status' => 'error',
                'message' => 'この操作にはログインが必要です。',
            ]);
        }

        Log::debug('begin TaskController.update');

        DB::transaction(function() use ($request) {
            $categoryId = $request->categoryId;
            if ($categoryId <= 0) {
                $category = Category::create([
                    'user_id' => Auth::id(),
                    'name'=> $request->categoryName,
                ]);
                
                $categoryId = $category->id;
                Log::debug('created category: ' . $categoryId);
            }

            Task::where(['id' => $request->id])->update([
                'id' => $request->id,
                'user_id' => Auth::id(),
                'category_id' => $categoryId,
                'name' => $request->name,
                'due_date'=> $request->dueDate,
                'color' => $request->color,
                'description'=> $request->description,
                'status' => $request->status,
            ]);
        });
        
        $task = Task::where(['id' => $request->id])->get()[0];
        $task->category_name = $task->category->name;

        Log::debug('finish TaskController.update');
        return response()->json([
            'status' => 'ok',
            'task' => $task
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        if (!Auth::check()) {
            return redirect('login');
        }

        Task::where(['id' => $id])->delete();
        return response()->json([
            'status' => 'ok',
            'id' => $id,
        ]);
    }
}
