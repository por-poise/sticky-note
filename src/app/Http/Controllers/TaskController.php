<?php

namespace App\Http\Controllers;


use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Carbon\CarbonImmutable;

use App\Models\User;
use App\Models\Category;
use App\Models\Task;

class TaskController extends Controller
{
    /**
     * タスク一覧
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
     * タスク登録
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
     * タスク編集
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
     * タスク更新
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
     * タスク削除
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

    /**
     * カレンダー表示
     */
    public function calendar(Request $request)
    {
        if (!Auth::check()) {
            return redirect('login');
        }

        // 基準月を取得
        $baseMonth = null;
        if ($request->base_month && preg_match('/[0-9]{6}/i', $request->base_month)) {
            $baseMonth = CarbonImmutable::parse($request->base_month . '01');
        } else {
            $baseMonth = CarbonImmutable::parse(CarbonImmutable::now()->format('Y-m'));
        }
        $startDate = $baseMonth->startOfMonth();
        $endDate = $baseMonth->endOfMonth();
        $expandedStartDate = $startDate->addDays(-1 * $startDate->dayOfWeek());
        $expandedEndDate = $endDate->addDays(7 - $endDate->dayOfWeek() - 1);

        // タスクを取得
        $tasks = Task::where('user_id', '=',  Auth::id())
        ->where('due_date', '>=', $expandedStartDate)
        ->where('due_date', '<=', $expandedEndDate)
        ->get();

        // 日付毎にタスクをグループ化
        $groupByDueDateTasks = [];
        foreach ($tasks as $task) {
            $key = $task->due_date;
            if (array_key_exists($key, $groupByDueDateTasks)) {
                $groupByDueDateTasks[$key][] = $task;
            } else {
                $groupByDueDateTasks[$key] = [$task];
            }
        }

        Log::debug($groupByDueDateTasks);

        return view('tasks.calendar', [
            'tasks' => $groupByDueDateTasks,
            'startDate' => $startDate,
            'endDate' => $endDate,
            'expandedStartDate' => $expandedStartDate,
            'expandedEndDate' => $expandedEndDate,
        ]);
    }
}
