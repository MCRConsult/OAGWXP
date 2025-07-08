<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Symfony\Component\HttpFoundation\Response;

// สร้าง Custom Middleware
class CheckPermission
{
    public function handle(Request $request, Closure $next, string $permission): Response
    {
        // ตรวจสอบว่าผู้ใช้ login หรือไม่
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        // ตรวจสอบ permission
        if (!Gate::allows($permission)) {
            abort(403, 'ไม่มีสิทธิ์เข้าถึงหน้านี้ กรุณาแจ้งผู้ดูแลระบบ');
        }
        
        return $next($request);
    }
}
