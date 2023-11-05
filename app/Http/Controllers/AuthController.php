<?php

namespace App\Http\Controllers;

use App\Models\MasterUser;
use App\Models\MasterUserLogin;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Laravel\Socialite\Facades\Socialite;

class AuthController extends BaseController
{
    /**
     * サインアップAPI
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse|mixed
     */

    public function signup(Request $request)
    {
        // パラメータのバリデーションの定義
        $validator = \Illuminate\Support\Facades\Validator::make($request->all(), [
            'identifier' => 'required|email|max:255',
            'password' => 'required|max:255',
            'role' => 'required|integer|min:0|max:5',
            'platform' => 'required|integer|min:0|max:4',
            'lastname' => 'required|max:32',
            'firstname' => 'required|max:32',
            'lastname_kana' => 'max:32',
            'firstname_kana' => 'max:32',
            'gender' => 'integer|min:0|max:2',
            'birthday' => 'date',
            'email' => 'email|max:255',
            'tel' => 'max:16',
            'zip_code' => 'max:8',
            'address' => 'max:64',
        ]);

        // パラメータのバリデーション
        if ($validator->fails()) {
            return response()->json([
                'result' => 200,
                'error' => $validator->errors(),
            ]);
        }

        try {
            // ユーザログイン情報( master_user_logins )を取得する
            $identifier = $request->input('identifier');
            $userLogin = MasterUserLogin::where(['identifier' => $identifier])->first();

            // すでに登録済みの場合、エラーにする
            if (!is_null($userLogin)) {
                $response = [
                    'result' => 300,
                    'message' => trans('messages.MSG_00001'),
                ];
                return response()->json($response);
            }

            // access_tokenを生成する
            $access_token = $this->generateRandomHash(64);

            // ユーザ情報( master_users )に登録する
            $user = MasterUser::create([
                'role' => $request->input('role'),
                'access_token' => $access_token,
                'lastname' => $request->input('lastname'),
                'firstname' => $request->input('firstname'),
                'lastname_kana' => $request->input('lastname_kana'),
                'firstname_kana' => $request->input('firstname_kana'),
                'gender' => $request->input('gender'),
                'birthday' => $request->input('birthday'),
                'email' => $request->input('email'),
                'tel' => $request->input('tel'),
                'zip_code' => $request->input('zip_code'),
                'address' => $request->input('address'),
            ]);

            // ユーザログイン情報( master_user_logins )に登録する
            $hashedPassword = Hash::make($request->input('password'));
            MasterUserLogin::create([
                'user_id' => $user->id,
                'platform' => $request->input('platform'),
                'identifier' => $request->input('identifier'),
                'password' => $hashedPassword,
                'push_token' => $access_token,
            ]);

            // 返却する
            $response = [
                'result' => 100,
                'message' => trans('messages.MSG_00002'),
                'access_token' => $user->access_token,
                'user_id' => $user->id,
            ];
            return response()->json($response);
        } catch (\Exception $e) {
            Log::error('Error Log:');
            Log::error($e);

            // 返却する
            return response()->json([
                'result' => 400,
                'message' => trans('messages.MSG_00006'),
            ]);
        }
    }

    /**
     * ログインAPI
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse|mixed
     */

    public function login(Request $request)
    {
        // パラメータのバリデーションの定義
        $validator = \Illuminate\Support\Facades\Validator::make($request->all(), [
            'identifier' => 'required|email|max:255',
            'password' => 'required|max:255',
            'platform' => 'required|integer|min:0|max:4',
        ]);

        // パラメータのバリデーション
        if ($validator->fails()) {
            return response()->json([
                'result' => 200,
                'error' => $validator->errors(),
            ]);
        }

        try {
            // ログインIDでユーザログイン情報( master_user_logins )を取得する
            $identifier = $request->input('identifier');
            $userLogin = MasterUserLogin::where(['identifier' => $identifier])
                ->whereNull('deleted_at')->first();

            // ユーザログイン情報( master_user_logins )が存在しない場合、エラーにする
            if (is_null($userLogin)) {
                $response = [
                    'result' => 200,
                    'message' => trans('messages.MSG_00003'),
                ];
                return response()->json($response);
            }

            // パスワードの確認する
            $passwordFromUserInput = $request->input('password');
            $hashedPasswordFromDatabase = $userLogin->password;
            if (!Hash::check($passwordFromUserInput, $hashedPasswordFromDatabase)) {
                $response = [
                    'result' => 200,
                    'message' => trans('messages.MSG_00008'),
                ];
                return response()->json($response);
            }

            // ユーザ情報( master_users )を取得する
            $user = MasterUser::find($userLogin->user_id);
            if (!is_null($user->deleted_at)) {
                $response = [
                    'result' => 200,
                    'message' => 'ユーザがすでに削除されました。',
                ];
                return response()->json($response);
            }

            // ユーザ情報( master_users )の値を設定する
            $access_token = $this->generateRandomHash(64);
            $user->access_token = $access_token;
            $user->updated_at = Carbon::now();

            // ユーザ情報( master_users )を更新する
            $user->save();

            // 返却する
            $response = [
                'result' => 100,
                'message' => trans('messages.MSG_00004'),
                'access_token' => $access_token,
                'user_id' => $userLogin->user_id,
            ];
            return response()->json($response);
        } catch (\Exception $e) {
            Log::error('Error Log:');
            Log::error($e);

            // 返却する
            return response()->json([
                'result' => 400,
                'message' => trans('messages.MSG_00006'),
            ]);
        }
    }

    /**
     * ログアウトAPI
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse|mixed
     */

    public function logout(Request $request)
    {
        // アクセストークン（Header）は必須チェック
        $access_token = $request->header('access_token');
        if ($access_token == null) {
            $response = [
                'result' => 200,
                'message' => trans('messages.MSG_00010'),
            ];
            return response()->json($response);
        }

        // パラメータのバリデーションの定義
        $validator = \Illuminate\Support\Facades\Validator::make($request->all(), [
            'user_id' => 'required|integer',
        ]);

        // パラメータのバリデーション
        if ($validator->fails()) {
            return response()->json([
                'result' => 200,
                'error' => $validator->errors(),
            ]);
        }

        try {
            // ユーザ情報( master_users )を取得する
            $user_id = $request->input('user_id');
            $user = MasterUser::where(['id' => $user_id])
                ->whereNull('deleted_at')->first();

            // ユーザ情報( master_users )が存在しない場合、エラーにする
            if (is_null($user)) {
                $response = [
                    'result' => 200,
                    'message' => trans('messages.MSG_00003'),
                ];
                return response()->json($response);
            }

            // ユーザ情報( master_users )の値を設定する
            $user->access_token = null;
            $user->updated_at = Carbon::now();

            // ユーザ情報( master_users )を更新する
            $user->save();

            // 返却する
            $response = [
                'result' => 100,
                'message' => trans('messages.MSG_00005'),
            ];
            return response()->json($response);
        } catch (\Exception $e) {
            Log::error('Error Log:');
            Log::error($e);

            // 返却する
            return response()->json([
                'result' => 400,
                'message' => trans('messages.MSG_00006'),
            ]);
        }
    }

    /**
     * Googleログイン
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse|\Symfony\Component\HttpFoundation\RedirectResponse
     */

    public function redirectToGoogle(Request $request)
    {
        // GoogleログインURLへRedirectする
        return Socialite::driver('google')->stateless()->redirect();
    }

    /**
     * GoogleログインCallback
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse|mixed
     */

    public function handleGoogleCallback(Request $request)
    {
        // Googleのアカウント情報を取得する
        $user = Socialite::driver('google')->stateless()->user();

        // メールアドレスを取得する
        $identifier = $user->getEmail();

        try {
            // ログインIDでユーザログイン情報( master_user_logins )を取得する
            $userLogin = MasterUserLogin::where(['identifier' => $identifier])
                ->whereNull('deleted_at')
                ->first();

            // ユーザログイン情報( master_user_logins )が存在しない場合、エラーにする
            if (is_null($userLogin)) {
                $response = [
                    'result' => 200,
                    'message' => trans('messages.MSG_00003'),
                ];
                return response()->json($response);
            }

            // ユーザ情報( master_users )を取得する
            $user = MasterUser::find($userLogin->user_id);
            if (!is_null($user->deleted_at)) {
                $response = [
                    'result' => 200,
                    'message' => 'ユーザがすでに削除されました。',
                ];
                return response()->json($response);
            }
            // ユーザ情報( master_users )の値を設定する
            $token = $this->generateRandomHash(64);
            $user->access_token = $token;
            $user->updated_at = Carbon::now();

            // ユーザ情報( master_users )を更新する
            $user->save();

            // 返却する
            $response = [
                'result' => 100,
                'message' => trans('messages.MSG_00004'),
                'access_token' => $token,
                'user_id' => $userLogin->user_id,
            ];
            return response()->json($response);
        } catch (\Exception $e) {
            Log::error('Error Log:');
            Log::error($e);

            // 返却する
            return response()->json([
                'result' => 400,
                'message' => trans('messages.MSG_00006'),
            ]);
        }
    }

    /**
     * LINEログイン
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse|mixed
     */

    public function redirectToLine(Request $request)
    {
        // RedirectのURLを作成する
        $state = bin2hex(random_bytes(16));
        $lineClientId = env('LINE_CLIENT_ID');
        $lineRedirectUri = env('LINE_REDIRECT_URI');
        $info = array(
            'response_type' => 'code',
            'client_id' => $lineClientId,
            'redirect_uri' => $lineRedirectUri,
            'state' => $state,
            'scope' => 'openid email',
        );
        $lineAuthUrl = 'https://access.line.me/oauth2/v2.1/authorize?' . http_build_query($info);

        // ログインURLへRedirectする
        return redirect()->away($lineAuthUrl);
    }

    /**
     * LINEログインCallback
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse|mixed|string
     */

    public function handleLineCallback(Request $request)
    {
        // リクエストを発行する
        $client = new Client();
        $response = $client->post('https://api.line.me/oauth2/v2.1/token', [
            'form_params' => [
                'grant_type' => 'authorization_code',
                'code' => $request->input('code'),
                'client_id' => env('LINE_CLIENT_ID'),
                'client_secret' => env('LINE_CLIENT_SECRET'),
                'redirect_uri' => env('LINE_REDIRECT_URI'),
            ],
        ]);

        // ログインIDを取得する
        $lineData = json_decode($response->getBody());
        $idToken = $lineData->id_token;
        $userInfo = json_decode(base64_decode(explode('.', $idToken)[1]));
        $identifier = $userInfo->email;

        try {
            // ログインIDでユーザログイン情報( master_user_logins )を取得する
            $userLogin = MasterUserLogin::where(['identifier' => $identifier])
                ->whereNull('deleted_at')
                ->first();

            // ユーザログイン情報( master_user_logins )が存在しない場合、エラーにする
            if (is_null($userLogin)) {
                $response = [
                    'result' => 200,
                    'message' => trans('messages.MSG_00003'),
                ];
                return response()->json($response);
            }

            // ユーザ情報( master_users )を取得する
            $user = MasterUser::find($userLogin->user_id);
            if (!is_null($user->deleted_at)) {
                $response = [
                    'result' => 200,
                    'message' => 'ユーザがすでに削除されました。',
                ];
                return response()->json($response);
            }

            // ユーザ情報( master_users )の値を設定する
            $token = $this->generateRandomHash(64);
            $user->access_token = $token;
            $user->updated_at = Carbon::now();

            // ユーザ情報( master_users )を更新する
            $user->save();

            // 返却する
            $response = [
                'result' => 100,
                'message' => trans('messages.MSG_00004'),
                'access_token' => $token,
                'user_id' => $userLogin->user_id,
            ];
            return response()->json($response);
        } catch (\Exception $e) {
            Log::error('Error Log:');
            Log::error($e);

            // 返却する
            return response()->json([
                'result' => 400,
                'message' => trans('messages.MSG_00006'),
            ]);
        }
    }

    /**
     * Facebookログイン
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse|\Symfony\Component\HttpFoundation\RedirectResponse
     */

    public function redirectToFacebook(Request $request)
    {
        // FacebookログインURLへRedirectする
        return Socialite::driver('facebook')->stateless()->redirect();
    }

    /**
     * FacebookログインCallback
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse|mixed
     */

    public function handleFacebookCallback(Request $request)
    {
        // Facebookのアカウント情報を取得する
        $user = Socialite::driver('facebook')->stateless()->user();

        // メールアドレスを取得する
        $identifier = $user->getEmail();

        try {
            // ログインIDでユーザログイン情報( master_user_logins )を取得する
            $userLogin = MasterUserLogin::where(['identifier' => $identifier])
                ->whereNull('deleted_at')
                ->first();

            // ユーザログイン情報( master_user_logins )が存在しない場合、エラーにする
            if (is_null($userLogin)) {
                $response = [
                    'result' => 200,
                    'message' => trans('messages.MSG_00003'),
                ];
                return response()->json($response);
            }

            // ユーザ情報( master_users )を取得する
            $user = MasterUser::find($userLogin->user_id);
            if (!is_null($user->deleted_at)) {
                $response = [
                    'result' => 200,
                    'message' => 'ユーザがすでに削除されました。',
                ];
                return response()->json($response);
            }

            // ユーザ情報( master_users )の値を設定する
            $token = $this->generateRandomHash(64);
            $user->access_token = $token;
            $user->updated_at = Carbon::now();

            // ユーザ情報( master_users )を更新する
            $user->save();

            // 返却する
            $response = [
                'result' => 100,
                'message' => trans('messages.MSG_00004'),
                'access_token' => $token,
                'user_id' => $userLogin->user_id,
            ];
            return response()->json($response);
        } catch (\Exception $e) {
            Log::error('Error Log:');
            Log::error($e);

            // 返却する
            return response()->json([
                'result' => 400,
                'message' => trans('messages.MSG_00006'),
            ]);
        }
    }

    /**
     * Appleログイン
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse|\Symfony\Component\HttpFoundation\RedirectResponse
     */

    public function redirectToApple(Request $request)
    {
        // AppleログインURLへRedirectする
        return Socialite::driver('apple')->stateless()->redirect();
    }

    /**
     * AppleログインCallback
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse|mixed
     */

    public function handleAppleCallback(Request $request)
    {
        // Appleのアカウント情報を取得する
        $user = Socialite::driver('apple')->stateless()->user();

        // メールアドレスを取得する
        $identifier = $user->getEmail();

        try {
            // ログインIDでユーザログイン情報( master_user_logins )を取得する
            $userLogin = MasterUserLogin::where(['identifier' => $identifier])
                ->whereNull('deleted_at')
                ->first();

            // ユーザログイン情報( master_user_logins )が存在しない場合、エラーにする
            if (is_null($userLogin)) {
                $response = [
                    'result' => 200,
                    'message' => trans('messages.MSG_00003'),
                ];
                return response()->json($response);
            }

            // ユーザ情報( master_users )を取得する
            $user = MasterUser::find($userLogin->user_id);
            if (!is_null($user->deleted_at)) {
                $response = [
                    'result' => 200,
                    'message' => 'ユーザがすでに削除されました。',
                ];
                return response()->json($response);
            }

            // ユーザ情報( master_users )の値を設定する
            $token = $this->generateRandomHash(64);
            $user->access_token = $token;
            $user->updated_at = Carbon::now();

            // ユーザ情報( master_users )を更新する
            $user->save();

            // 返却する
            $response = [
                'result' => 100,
                'message' => trans('messages.MSG_00004'),
                'access_token' => $token,
                'user_id' => $userLogin->user_id,
            ];
            return response()->json($response);
        } catch (\Exception $e) {
            Log::error('Error Log:');
            Log::error($e);

            // 返却する
            return response()->json([
                'result' => 400,
                'message' => trans('messages.MSG_00006'),
            ]);
        }
    }

    /**
     * Emailチェック
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse|mixed
     */

     public function loginWithEmail(Request $request)
     {
         // メールアドレスを取得する
         $identifier = $request->input('email');

         try {
             // ログインIDでユーザログイン情報( master_user_logins )を取得する
             $userLogin = MasterUserLogin::where(['identifier' => $identifier])
                 ->whereNull('deleted_at')
                 ->first();

             // ユーザログイン情報( master_user_logins )が存在しない場合、エラーにする
             if (is_null($userLogin)) {
                 $response = [
                     'result' => 200,
                     'message' => trans('messages.MSG_00003'),
                 ];
                 return response()->json($response);
             }

             // ユーザ情報( master_users )を取得する
             $user = MasterUser::find($userLogin->user_id);
             if (!is_null($user->deleted_at)) {
                 $response = [
                     'result' => 200,
                     'message' => 'ユーザがすでに削除されました。',
                 ];
                 return response()->json($response);
             }
             // ユーザ情報( master_users )の値を設定する
             $token = $this->generateRandomHash(64);
             $user->access_token = $token;
             $user->updated_at = Carbon::now();

             // ユーザ情報( master_users )を更新する
             $user->save();

             // 返却する
             $response = [
                 'result' => 100,
                 'message' => trans('messages.MSG_00004'),
                 'access_token' => $token,
                 'user_id' => $userLogin->user_id,
             ];
             return response()->json($response);
         } catch (\Exception $e) {
             Log::error('Error Log:');
             Log::error($e);

             // 返却する
             return response()->json([
                 'result' => 400,
                 'message' => trans('messages.MSG_00006'),
             ]);
         }
     }
}
