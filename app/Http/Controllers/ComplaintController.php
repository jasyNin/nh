<?php

namespace App\Http\Controllers;

use App\Models\Complaint;
use Illuminate\Http\Request;

class ComplaintController extends Controller
{
    /**
     * Показывает список жалоб
     */
    public function index()
    {
        $complaints = Complaint::with('user')->orderBy('created_at', 'desc')->paginate(20);
        return view('admin.complaints.index', compact('complaints'));
    }

    /**
     * Показывает детали жалобы
     */
    public function show(Complaint $complaint)
    {
        $complaint->load('user', 'complaintable');
        return view('admin.complaints.show', compact('complaint'));
    }

    /**
     * Обновляет статус жалобы
     */
    public function updateStatus(Request $request, Complaint $complaint)
    {
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
    }

    /**
     * Удаляет жалобу
     */
    public function destroy(Complaint $complaint)
    {
        $complaint->delete();
        
        if (request()->ajax() || request()->wantsJson()) {
            return response()->json([
                'message' => 'Жалоба успешно удалена',
                'complaint_id' => $complaint->id
            ]);
        }
        
        return redirect()->route('admin.complaints.index')->with('success', 'Жалоба успешно удалена');
    }
} 