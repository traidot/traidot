<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\MasterUser;

/**
 * AccessTokenチェック
 */
class AccessToken
{
    /**
     * Summary of handle
     * @param mixed $request
     * @param \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $access_token = $request->header('access_token');
        if ($access_token == null) {
            $response = [
                'result' => 200,
                'message' => trans('messages.MSG_00010')
            ];
            return response()->json($response);
        }

        // パラメータのバリデーションの定義
        $validator = \Illuminate\Support\Facades\Validator::make($request->all(), [
            'user_id' => 'required|integer'
        ]);

        // パラメータのバリデーション
        if ($validator->fails()) {
            return response()->json([
                'result' => 200,
                'error' => $validator->errors()
            ]);
        }

        // ユーザ情報(master_users)を取得する
        $user_id = $request->input('user_id');
        $user = MasterUser::where(['id' => $user_id])
            ->whereNull('deleted_at')->first();

        // ユーザ情報(master_users)が存在しない場合、エラーにする
        if (is_null($user)) {
            $response = [
                'result' => 200,
                'message' => trans('messages.MSG_00003')
            ];
            return response()->json($response);
        }

        // アクセストークンは一致するチェック
        if ($access_token !== $user->access_token) {
            $response = [
                'result' => 200,
                'message' => trans('messages.MSG_00009')
            ];
            return response()->json($response);
        }

        return $next($request);
    }
}
