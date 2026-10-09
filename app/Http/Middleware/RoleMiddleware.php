<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, string $role)
    {
        $user = $request->user();
        if (! $user) {
            return redirect()->route('login');
        }
        if ($user->role !== $role) {
            // Santri yang mencoba buka area musrif (atau sebaliknya) dikembalikan ke dashboard-nya sendiri
            return redirect('/' . $user->role . '/dashboard')->with('error', 'Anda tidak punya akses ke halaman itu.');
        }
        return $next($request);
    }
}
