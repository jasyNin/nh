<?php

namespace App\Http\Controllers;

use App\Models\Complaint;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ComplaintController extends Controller
{
    /**
     * Показывает список жалоб
     */
    public function index()
    {
        try {
            $complaints = Complaint::with(['user', 'complaintable'])
                ->latest()
                ->paginate(10);

            return view('admin.complaints.index', compact('complaints'));
        } catch (\Exception $e) {
            Log::error('Ошибка при загрузке списка жалоб: ' . $e->getMessage());
            return view('admin.complaints.index', ['complaints' => collect([]), 'error' => 'Произошла ошибка при загрузке списка жалоб.']);
        }
    }

    /**
     * Показывает детали жалобы
     */
    public function show(Complaint $complaint)
    {
        try {
            // Загружаем связанные модели
            $complaint->load(['user', 'complaintable']);
            
            // Проверяем, существует ли связанная модель
            if (!$complaint->complaintable) {
                return redirect()->route('admin.complaints.index')
                    ->with('error', 'Объект жалобы не найден. Возможно, он был удален.');
            }
            
            // Загружаем дополнительные связи в зависимости от типа объекта
            $type = str_replace('App\\Models\\', '', $complaint->complaintable_type);
            
            // Используем безопасный подход без прямых ссылок на классы моделей
            if (strpos($complaint->complaintable_type, 'Comment') !== false) {
                if (method_exists($complaint->complaintable, 'load')) {
                    $complaint->complaintable->load(['post', 'user']);
                }
            } elseif (strpos($complaint->complaintable_type, 'CommentReply') !== false) {
                if (method_exists($complaint->complaintable, 'load')) {
                    $complaint->complaintable->load(['comment.post', 'comment.user', 'user']);
                }
            } elseif (strpos($complaint->complaintable_type, 'Post') !== false) {
                if (method_exists($complaint->complaintable, 'load')) {
                    $complaint->complaintable->load(['user']);
                }
            }
            
            return view('admin.complaints.show', compact('complaint'));
        } catch (\Exception $e) {
            Log::error('Ошибка при просмотре жалобы: ' . $e->getMessage(), [
                'complaint_id' => $complaint->id,
                'exception' => $e
            ]);
            
            return redirect()->route('admin.complaints.index')
                ->with('error', 'Произошла ошибка при загрузке жалобы. Пожалуйста, попробуйте позже.');
        }
    }

    /**
     * Обновляет статус жалобы
     */
    public function updateStatus(Request $request, Complaint $complaint)
    {
        try {
            $request->validate([
                'status' => 'required|in:new,open,unjustified,closed'
            ]);

            $complaint->update([
                'status' => $request->status
            ]);

            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'message' => 'Статус жалобы успешно обновлен',
                    'complaint' => $complaint
                ]);
            }

            return redirect()->route('admin.complaints.index')->with('success', 'Статус жалобы успешно обновлен');
        } catch (\Exception $e) {
            Log::error('Ошибка при обновлении статуса жалобы: ' . $e->getMessage());
            
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'error' => 'Произошла ошибка при обновлении статуса жалобы'
                ], 500);
            }
            
            return redirect()->route('admin.complaints.index')
                ->with('error', 'Произошла ошибка при обновлении статуса жалобы');
        }
    }

    /**
     * Удаляет жалобу
     */
    public function destroy(Complaint $complaint)
    {
        try {
            $complaint->delete();
            
            if (request()->ajax() || request()->wantsJson()) {
                return response()->json([
                    'message' => 'Жалоба успешно удалена',
                    'complaint_id' => $complaint->id
                ]);
            }
            
            return redirect()->route('admin.complaints.index')->with('success', 'Жалоба успешно удалена');
        } catch (\Exception $e) {
            Log::error('Ошибка при удалении жалобы: ' . $e->getMessage());
            
            if (request()->ajax() || request()->wantsJson()) {
                return response()->json([
                    'error' => 'Произошла ошибка при удалении жалобы'
                ], 500);
            }
            
            return redirect()->route('admin.complaints.index')
                ->with('error', 'Произошла ошибка при удалении жалобы');
        }
    }

    /**
     * Создает новую жалобу
     */
    public function store(Request $request)
    {
        try {
            $request->validate([
                'complaintable_id' => 'required|integer',
                'complaintable_type' => 'required|string',
                'type' => 'required|string',
                'reason' => 'required|string|min:10'
            ]);
            
            $complaint = Complaint::create([
                'user_id' => auth()->id(),
                'complaintable_id' => $request->complaintable_id,
                'complaintable_type' => $request->complaintable_type,
                'type' => $request->type,
                'reason' => $request->reason,
                'status' => 'new'
            ]);
            
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'message' => 'Жалоба успешно отправлена',
                    'complaint' => $complaint
                ]);
            }
            
            return redirect()->back()->with('success', 'Жалоба успешно отправлена');
        } catch (\Exception $e) {
            Log::error('Ошибка при создании жалобы: ' . $e->getMessage());
            
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'error' => 'Произошла ошибка при отправке жалобы'
                ], 500);
            }
            
            return redirect()->back()->with('error', 'Произошла ошибка при отправке жалобы');
        }
    }
} 