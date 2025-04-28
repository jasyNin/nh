<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class SqlInjectionProtection
{
    public function handle(Request $request, Closure $next)
    {
        $input = $request->all();
        
        array_walk_recursive($input, function(&$value) {
            if (is_string($value)) {
                // Удаляем потенциально опасные SQL-символы
                $value = preg_replace('/[\x00-\x1F\x7F-\xFF]/', '', $value);
                $value = str_replace(['\'', '"', '`', ';', '--', '/*', '*/'], '', $value);
            }
        });
        
        $request->merge($input);
        
        return $next($request);
    }
} 