<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests\LoginRequest;
use App\Http\Resources\UserCollection;

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
            return response([
                'message' => $e->getMessage(),
                'code' => Response::HTTP_INTERNAL_SERVER_ERROR,
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Get authenticated user info.
     *
     * @param Illuminate\Http\Request $request
     * @return mixed
     */
    public function info(Request $request)
    {
        try {
            return UserCollection::make($request->user());
        } catch (\Exception $e) {
            return response([
                'message' => $e->getMessage(),
                'code' => Response::HTTP_INTERNAL_SERVER_ERROR,
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}