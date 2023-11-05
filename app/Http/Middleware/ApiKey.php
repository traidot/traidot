<?php

namespace App\Http\Middleware;

use Closure;

/**
 * APP KEYチェック
 */
class ApiKey
{
    /**
     * Summary of handle
     * @param mixed $request
     * @param \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $apiKeyFromEnv = env('API_KEY');
        $apiKeyFromRequest = $request->header('api-key');

        if ($apiKeyFromEnv !== $apiKeyFromRequest) {
            $response = [
                'result' => 200,
                'message' => trans('messages.MSG_00007')
            ];

            return response()->json($response);
        }

        return $next($request);
    }
}
