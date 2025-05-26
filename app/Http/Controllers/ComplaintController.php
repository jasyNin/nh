<?php

namespace App\Http\Controllers;

use App\Models\Complaint;
use App\Models\Comment;
use App\Models\CommentReply;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class ComplaintController extends Controller
{
    /**
     * Показывает список жалоб
     */
    public function index()
    {
        $complaints = Complaint::with(['user', 'complaintable'])
            ->latest()
            ->paginate(20);

        $complaintsCount = Complaint::where('status', 'new')->count();

        return view('moderator.reports.index', compact('complaints', 'complaintsCount'));
    }

    /**
     * Показывает детали жалобы
     */
    public function show(Complaint $complaint)
    {
        $complaint->load(['user', 'complaintable.user']);
        return view('moderator.reports.show', compact('complaint'));
    }

    /**
     * Обновляет статус жалобы
     */
    public function update(Request $request, Complaint $complaint)
    {
        $validated = $request->validate([
            'status' => 'required|in:in_progress,resolved,rejected',
            'moderator_comment' => 'required|string|min:10'
        ]);

        $complaint->update([
            'status' => $validated['status'],
            'moderator_comment' => $validated['moderator_comment'],
            'resolved_at' => now()
        ]);

        return response()->json([
            'message' => 'Статус жалобы обновлен',
            'complaint' => $complaint
        ]);
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
        \Log::info('Получен запрос на создание жалобы', [
            'request_data' => $request->all(),
            'user_id' => Auth::id()
        ]);

        $validated = $request->validate([
            'complaintable_id' => 'required|integer',
            'complaintable_type' => 'required|string',
            'type' => 'required|string',
            'reason' => 'required|string|min:10',
            'target_type' => 'required|string'
        ]);

        \Log::info('Данные валидированы', ['validated_data' => $validated]);

        try {
            // Проверяем существование объекта жалобы
            $complaintableClass = $validated['complaintable_type'];
            if (!class_exists($complaintableClass)) {
                return redirect()->back()->with('error', 'Неверный тип объекта жалобы');
            }
            
            // Проверяем, что тип объекта соответствует ожидаемому
            if (!in_array($complaintableClass, [Comment::class, CommentReply::class])) {
                return redirect()->back()->with('error', 'Неподдерживаемый тип объекта жалобы');
            }
            
            $complaintable = $complaintableClass::find($validated['complaintable_id']);
            
            if (!$complaintable) {
                return redirect()->back()->with('error', 'Объект жалобы не найден');
            }

            $complaint = Complaint::create([
                'user_id' => Auth::id(),
                'complaintable_id' => $validated['complaintable_id'],
                'complaintable_type' => $validated['complaintable_type'],
                'type' => $validated['type'],
                'reason' => $validated['reason'],
                'target_type' => $validated['target_type'],
                'status' => 'new'
            ]);

            \Log::info('Жалоба успешно создана', ['complaint_id' => $complaint->id]);

            return redirect()->back()->with('success', 'Жалоба успешно отправлена');
        } catch (\Exception $e) {
            \Log::error('Ошибка при создании жалобы: ' . $e->getMessage(), [
                'exception' => $e,
                'request_data' => $request->all()
            ]);
            return redirect()->back()->with('error', 'Произошла ошибка при отправке жалобы');
        }
    }
} 