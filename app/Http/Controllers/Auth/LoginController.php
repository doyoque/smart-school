<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;
use App\Http\Requests\LoginRequest;

class LoginController extends Controller
{
    /**
     * Handling user login.
     *
     * @param App\Http\Requests\LoginRequest $request
     * @param mixed
     */
    public function login(LoginRequest $request)
    {
        try {
            $postData = $request->validated();

            if (Auth::attempt($request->getCredentials())) {
                $user = Auth::user();

                $data['message'] = 'Logged in.';
                $data['user'] = [
                    'id' => $user->id,
                    'name' => $user->name,
                    'usernmae' => $user->username,
                    'email' => $user->email,
                ];
                $data['role'] = $user->role->name;
                $data['token'] = $user->createToken('sSchool')->accessToken;
                $data['code'] = Response::HTTP_OK;

                return response($data, Response::HTTP_OK);
            }

            return response([
                'message' => 'Credential is wrong.',
                'code' => Response::HTTP_UNAUTHORIZED,
            ], Response::HTTP_UNAUTHORIZED);
        } catch (\Exception $e) {
            Log::error(__FUNCTION__ . " auth Exception" . $e->getMessage(), $e->getTrace());
            return response([
                'message' => $e->getMessage(),
                'code' => Response::HTTP_INTERNAL_SERVER_ERROR,
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Handling user logout.
     *
     * @param Illuminate\Http\Request $request
     * @return mixed
     */
    public function logout(Request $request)
    {
        try {
            if (Auth::check()) {
                Auth::user()->token()->revoke();

                return response([
                    'message' => 'Log out.',
                    'code' => Response::HTTP_OK,
                ], Response::HTTP_OK);
            }
        } catch (\Exception $e) {
            Log::error(__FUNCTION__ . " auth Exception" . $e->getMessage(), $e->getTrace());
            return response([
                'message' => $e->getMessage(),
                'code' => Response::HTTP_INTERNAL_SERVER_ERROR,
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
