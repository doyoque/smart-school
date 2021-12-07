<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Models\User;
use App\Models\School;

class SignupController extends Controller
{
    /**
     * Handle signup.
     *
     * @param Illuminate\Http\Request $request
     * @return mixed
     */
    public function signup(Request $request)
    {
        try {
            $validation = Validator::make($request->all(), [
                'name' => ['required'],
                'username' => ['required'],
                'email' => ['required', 'string', 'email', 'max:255'],
                'password' => ['required', 'string', 'max:255', 'min:8'],
                'school_name' => ['required', 'string'],
            ]);

            if ($validation->fails()) {
                return response([
                    'error' => $validation->errors()->all(),
                    'code' => Response::HTTP_UNPROCESSABLE_ENTITY,
                ], Response::HTTP_UNPROCESSABLE_ENTITY);
            }

            if (User::where('email', '=', $request['email'])->count() > 0) {
                return response([
                    'message' => 'Cannot register with same email.',
                    'code' => Response::HTTP_UNPROCESSABLE_ENTITY,
                ], Response::HTTP_UNPROCESSABLE_ENTITY);
            }

            DB::beginTransaction();

            $school = School::create(['name' => $request['school_name']]);

            $user = User::create([
                'name' => $request['name'],
                'email' => $request['email'],
                'password' => Hash::make($request['password']),
                'username' => $request['username'],
                'role_id' => 1,
                'school_id' => $school->id,
                'remember_token' => Str::random(10),
            ]);

            DB::commit();
            return response([
                'message' => 'signup.',
                'token' => $user->createToken('sSchool')->accessToken,
                'code' => Response::HTTP_CREATED,
            ], Response::HTTP_CREATED);
        } catch (\Exception $e) {
            DB::rollback();
            return response([
                'message' => 'Internal server error.',
                'code' => Response::HTTP_INTERNAL_SERVER_ERROR,
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
