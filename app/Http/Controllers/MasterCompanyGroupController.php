<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MasterHomeGroup;
use Illuminate\Support\Facades\Log;

class MasterCompanyGroupController extends BaseController
{
    /**
     * 企業デバイスペア妥当性チェック
     * ユーザとデバイスのペアが妥当かをチェックする
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse|mixed
     */
    public function validateCompanyGroupDevice(Request $request)
    {
        // パラメータのバリデーションの定義
        $validator = \Illuminate\Support\Facades\Validator::make($request->all(), [
            'device_code' => 'required|max:32',
        ]);

        // パラメータのバリデーション
        if ($validator->fails()) {
            return response()->json([
                'result' => 200,
                'error' => $validator->errors()
            ]);
        }

        try {
            $userId = $request->input('user_id');
            $deviceCode = $request->input('device_code');

            // 検索する
            $result = MasterHomeGroup::select('master_home_groups.id')
                ->join('master_home_group_devices', 'master_home_groups.id', '=', 'master_home_group_devices.home_group_id')
                ->join('master_devices', 'master_home_group_devices.device_id', '=', 'master_devices.id')
                ->whereNull('master_home_groups.deleted_at')
                ->whereNull('master_home_group_devices.deleted_at')
                ->whereNull('master_devices.deleted_at')
                ->where('master_home_groups.user_id', $userId)
                ->where('master_devices.device_code', $deviceCode)
                ->get();

            if ($result->count() <= 0) {
                $response = [
                    'result' => 200,
                    'message' => 'データが存在しません。'
                ];
                return response()->json($response);
            }

            // 返却する
            $response = [
                'result' => 100,
                'message' => '正常に完了しました。',
            ];
            return response()->json($response);
        } catch (\Exception $e) {
            Log::error('Error Log:');
            Log::error($e);

            // 返却する
            return response()->json([
                'result' => 400,
                'message' => 'システムエラーが発生しました。'
            ]);
        }
    }
}
