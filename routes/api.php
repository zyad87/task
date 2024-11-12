<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TaskController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// مسار للحصول على بيانات المستخدم في حالة استخدام المصادقة عبر Sanctum
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// استخدام apiResource لتبسيط مسارات المهام CRUD

Route::get('tasks', [TaskController::class, 'index']);  // لعرض جميع المهام
Route::post('tasks', [TaskController::class, 'store']); // لإنشاء مهمة جديدة
Route::get('tasks/{id}', [TaskController::class, 'show']); // لعرض مهمة معينة
Route::put('tasks/{id}', [TaskController::class, 'update']); // لتحديث مهمة موجودة
Route::delete('tasks/{id}', [TaskController::class, 'destroy']); // لحذف مهمة موجودة
