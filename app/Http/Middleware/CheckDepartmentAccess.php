<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckDepartmentAccess
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
public function handle($request, Closure $next)
{
    $departmentId = $request->route('department') ?? $request->input('department_id');
    if (!$request->user()->canAccessDepartment($departmentId)) {
        abort(403, 'You do not have access to this department.');
    }
    return $next($request);
}
}
