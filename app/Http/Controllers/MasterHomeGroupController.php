<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\Models\MasterHomeGroup;
use Illuminate\Support\Facades\Log;
use App\Models\MasterHomeGroupDevice;
use App\Models\MasterHomeGroupMember;

class MasterHomeGroupController extends BaseController
{

    public function index(Request $request)
    {
        $userId = $request->input('user_id');
        try {
            $masterHomeGroup = MasterHomeGroup::where(['user_id' => $userId])
                ->whereNull('deleted_at')
                ->first();
            if (is_null($masterHomeGroup)) {
                $response = [
                    'result' => 200,
                    'message' => 'グループが存在しません。'
                ];
                return response()->json($response);
            }

            $masterHomeGroupMember = MasterHomeGroupMember::join('master_users', 'master_home_group_members.user_id', '=', 'master_users.id')
                ->select('master_home_group_members.id', 'master_users.lastname', 'master_users.firstname', 'master_users.email')
                ->whereNull('master_users.deleted_at')
                ->whereNull('master_home_group_members.deleted_at')
                ->where(['master_home_group_members.home_group_id' => $masterHomeGroup->id])
                ->get()->toArray();

            $masterHomeGroupDevice = MasterHomeGroupDevice::join('master_devices', 'master_home_group_devices.device_id', '=', 'master_devices.id')
                ->select('master_devices.id', 'master_devices.device_type', 'master_devices.device_code', 'master_devices.device_name', 'master_devices.remark')
                ->whereNull('master_devices.deleted_at')
                ->whereNull('master_home_group_devices.deleted_at')
                ->where(['master_home_group_devices.home_group_id' => $masterHomeGroup->id])
                ->get()->toArray();


            $group = array(
                'id' => $masterHomeGroup->id,
                'name' => $masterHomeGroup->name,
                'members' => $masterHomeGroupMember,
                'devices' => $masterHomeGroupDevice
            );

            // 返却する
            $response = [
                'result' => 100,
                'message' => trans('messages.MSG_00011'),
                'group' => $group,
            ];
            return response()->json($response);
        } catch (\Exception $e) {
            Log::error('Error Log:');
            Log::error($e);

            // 返却する
            return response()->json([
                'result' => 400,
                'message' => trans('messages.MSG_00006')
            ]);
        }
    }

    // TODO
    public function create(Request $request)
    {
        // パラメータのバリデーションの定義
        $validator = \Illuminate\Support\Facades\Validator::make($request->all(), [
            'name' => 'required|max:100',
        ]);

        // パラメータのバリデーション
        if ($validator->fails()) {
            return response()->json([
                'result' => 200,
                'error' => $validator->errors()
            ]);
        }

        try {
            // グループ(master_home_groups)に登録する
            $masterHomeGroup = MasterHomeGroup::create([
                'user_id' => $request->input('user_id'),
                'name' => $request->input('name')
            ]);

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


    //
    public function saveMember(Request $request)
    {
        // パラメータのバリデーションの定義
        $validator = \Illuminate\Support\Facades\Validator::make($request->all(), [
            'home_group_id' => 'required|integer',
            'lastname' => 'required|max:32',
            'firstname' => 'required|max:32',
            'email' => 'email|max:255',
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
            $homeGroupId = $request->input('home_group_id');
            $masterHomeGroup = MasterHomeGroup::
                where(['user_id' => $userId])->
                where(['id' => $homeGroupId])
                ->first();

            if (is_null($masterHomeGroup)) {
                $response = [
                    'result' => 200,
                    'message' => 'グループが存在しません。'
                ];
                return response()->json($response);
            }


            // グループ(master_home_groups)に登録する
            $masterHomeGroup = MasterHomeGroup::create([
                'user_id' => $request->input('user_id'),
                'name' => $request->input('name')
            ]);

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
    public function deleteMember(Request $request)
    {
        // パラメータのバリデーションの定義
        $validator = \Illuminate\Support\Facades\Validator::make($request->all(), [
            // TODO Không cần biến này
            'home_group_id' => 'required|integer',


            'home_group_member_id' => 'required|integer',
        ]);

        // パラメータのバリデーション
        if ($validator->fails()) {
            return response()->json([
                'result' => 200,
                'error' => $validator->errors()
            ]);
        }

        try {
            $homeGroupMemberId = $request->input('home_group_member_id');
            $masterHomeGroupMember = MasterHomeGroupMember::find($homeGroupMemberId);

            if (is_null($masterHomeGroupMember)) {
                $response = [
                    'result' => 200,
                    'message' => 'グループメンバーが存在しません。'
                ];
                return response()->json($response);
            }

            if (!is_null($masterHomeGroupMember->deleted_at)) {
                $response = [
                    'result' => 200,
                    'message' => 'グループメンバーがすでに削除されました。'
                ];
                return response()->json($response);
            }

            // グループメンバーを削除する
            $masterHomeGroupMember->deleted_at = Carbon::now();
            $masterHomeGroupMember->save();

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


    // TODO
    public function saveDevice(Request $request)
    {
        // アクセストークン（Header）は必須チェック
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
            'user_id' => 'required|integer',
            'home_group_id' => 'required|integer',
            'device_code' => 'required|max:32',
            'device_name' => 'required|max:64',
            'remark' => 'required|max:200',
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
            $homeGroupId = $request->input('home_group_id');
            $masterHomeGroupDevice = MasterHomeGroupDevice::
                where(['user_id' => $userId])->
                where(['id' => $homeGroupId])
                ->first();

            if (is_null($masterHomeGroupDevice)) {
                $response = [
                    'result' => 200,
                    'message' => 'グループが存在しません。'
                ];
                return response()->json($response);
            }

            // グループ(master_home_groups)に登録する
            $masterHomeGroup = MasterHomeGroup::create([
                'user_id' => $request->input('user_id'),
                'name' => $request->input('name')
            ]);

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
    public function deleteDevice(Request $request)
    {
        // アクセストークン（Header）は必須チェック
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
            // TODO Không cần biến này
            'home_group_id' => 'required|integer',

            'home_group_device_id' => 'required|integer',
        ]);

        // パラメータのバリデーション
        if ($validator->fails()) {
            return response()->json([
                'result' => 200,
                'error' => $validator->errors()
            ]);
        }

        try {
            $homeGroupDeviceId = $request->input('home_group_device_id');
            $masterHomeGroupDevice = MasterHomeGroupDevice::find($homeGroupDeviceId);

            if (is_null($masterHomeGroupDevice)) {
                $response = [
                    'result' => 200,
                    'message' => 'グループデバイスが存在しません。'
                ];
                return response()->json($response);
            }

            if (!is_null($masterHomeGroupDevice->deleted_at)) {
                $response = [
                    'result' => 200,
                    'message' => 'グループデバイスがすでに削除されました。'
                ];
                return response()->json($response);
            }

            // グループデバイスを削除する
            $masterHomeGroupDevice->deleted_at = Carbon::now();
            $masterHomeGroupDevice->save();



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
