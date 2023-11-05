<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Request;

class AnalysisController extends BaseController
{
    /**
     * Analysis監視開始
     *
     * @param \Illuminate\Support\Facades\Request $request
     * @return void
     */
    public function openAnalysis(Request $request)
    {
        try {
            $userId = $request->input('user_id');

            // TODO


            $mesurements = $request->input('');

            // 返却する
            $response = [
                'result' => 100,
                'message' => '正常に完了しました。',
                'mesurements' => $mesurements,
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

    /**
     * Analysis監視継続
     *
     * @param \Illuminate\Support\Facades\Request $request
     * @return void
     */
    public function updateAnalysis(Request $request)
    {
        try {
            $userId = $request->input('user_id');

            // TODO


            $mesurements = $request->input('');

            // 返却する
            $response = [
                'result' => 100,
                'message' => '正常に完了しました。',
                'mesurements' => $mesurements,
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

    /**
     * Analysis監視停止
     *
     * @param \Illuminate\Support\Facades\Request $request
     * @return void
     */
    public function closeAnalysis(Request $request)
    {
        try {
            $userId = $request->input('user_id');

            // TODO


            $mesurements = $request->input('');

            // 返却する
            $response = [
                'result' => 100,
                'message' => '正常に完了しました。',
                'mesurements' => $mesurements,
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
