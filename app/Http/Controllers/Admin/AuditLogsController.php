<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Symfony\Component\HttpFoundation\Response;

class AuditLogsController extends Controller
{
    public function index(Request $request)
    {
        abort_if(Gate::denies('audit_log_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $searchTerm = $request->get('search', '');
        $actionType = $request->get('action_type', '');
        $userFilter = $request->get('user_filter', '');
        $dateFrom   = $request->get('date_from', '');
        $dateTo     = $request->get('date_to', '');

        $query = AuditLog::with(['user'])->orderByDesc('created_at');

        // Full-text search
        if ($searchTerm) {
            $query->where(function ($q) use ($searchTerm) {
                $q->where('description', 'like', "%{$searchTerm}%")
                  ->orWhere('subject_type', 'like', "%{$searchTerm}%")
                  ->orWhere('properties', 'like', "%{$searchTerm}%")
                  ->orWhere('host', 'like', "%{$searchTerm}%")
                  ->orWhere('id', 'like', "%{$searchTerm}%")
                  ->orWhereHas('user', fn($u) =>
                      $u->where('name', 'like', "%{$searchTerm}%")
                        ->orWhere('email', 'like', "%{$searchTerm}%"));
            });
        }

        // Action type pill filter
        // - created / updated / deleted → match description column (set by Auditable trait)
        // - login  → Activity rows whose stored event/description says "login" / "logged in"
        // - logout → Activity rows whose stored event/description says "logout" / "logged out"
        if ($actionType) {
            if ($actionType === 'login') {
                // Rows audited from the Activity model where the captured login event lives in properties
                $query->where('subject_type', 'like', '%Activity%')
                      ->where(function ($q) {
                          $q->where('properties', 'like', '%login%')
                            ->orWhere('properties', 'like', '%logged in%');
                      });
            } elseif ($actionType === 'logout') {
                $query->where('subject_type', 'like', '%Activity%')
                      ->where(function ($q) {
                          $q->where('properties', 'like', '%logout%')
                            ->orWhere('properties', 'like', '%logged out%');
                      });
            } else {
                // created / updated / deleted — the Auditable trait stores "audit:created" etc.
                $query->where('description', 'like', "%audit:{$actionType}%");
            }
        }

        // User filter
        if ($userFilter) {
            $query->whereHas('user', fn($u) =>
                $u->where('name', 'like', "%{$userFilter}%")
                  ->orWhere('email', 'like', "%{$userFilter}%"));
        }

        // Date range filter
        if ($dateFrom) {
            $query->whereDate('created_at', '>=', $dateFrom);
        }
        if ($dateTo) {
            $query->whereDate('created_at', '<=', $dateTo);
        }

        $auditLogs = $query->paginate(100)->withQueryString();

        return view('admin.auditLogs.index', compact(
            'auditLogs', 'searchTerm', 'actionType', 'userFilter', 'dateFrom', 'dateTo'
        ));
    }

    public function show(AuditLog $auditLog)
    {
        abort_if(Gate::denies('audit_log_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $auditLog->load('user');

        return view('admin.auditLogs.show', compact('auditLog'));
    }
}