<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\HttpException;

class EnsureUserHasRole
{
    /**
     * الاستخدام في المسارات: ->middleware('role:admin,accountant')
     * يرفض الطلب بكود 403 إن لم يكن دور المستخدم الحالي ضمن القائمة المسموحة.
     * تطبيقًا فعليًا (وليس توثيقيًا فقط) لمصفوفة BR-SEC-01/02 في وثيقة Security & Infrastructure.
     */
    public function handle(Request $request, Closure $next, string ...$roles)
    {
        $user = $request->user();

        abort_if(! $user, 401, 'يجب تسجيل الدخول');

        if (! in_array($user->role, $roles, true)) {
            throw new HttpException(403, 'لا تملك صلاحية الوصول لهذا الإجراء — الدور المطلوب: ' . implode('/', $roles));
        }

        return $next($request);
    }
}
