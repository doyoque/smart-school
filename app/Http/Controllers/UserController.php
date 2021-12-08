<?php

namespace App\Http\Controllers;

use Illuminate\Http\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\UserRequest;
use App\Models\User;
use App\Models\Role;
use App\Http\Resources\UserCollection;

class UserController extends Controller
{
    /**
     * List user.
     *
     * @param Illuminate\Http\Request $request
     * @return mixed
     */
    public function index(Request $request)
    {
        try {
            return UserCollection::collection(User::search($request->query())->paginate(10));
        } catch (\Exception $e) {
            Log::error(__FUNCTION__ . " user Exception" . $e->getMessage(), $e->getTrace());
            return response([
                'message' => 'Internal server error.',
                'code' => Response::HTTP_INTERNAL_SERVER_ERROR,
            ]);
        }
    }

    /**
     * Store new user.
     *
     * @param App\Http\Requests\UserRequest $request
     * @return mixed
     */
    public function store(UserRequest $request)
    {
        try {
            $postData = $request->validated();

            $this->userExist($request['email']);

            User::create([
                'name' => $postData['name'],
                'username' => $postData['username'],
                'email' => $postData['email'],
                'role_id' => $postData['role_id'],
                'school_id' => $request->user()->school_id,
                'password' => Hash::make($postData['password']),
            ]);

            return response([
                'message' => 'User created.',
                'code' => Response::HTTP_CREATED,
            ], Response::HTTP_CREATED);
        } catch (\Exception $e) {
            Log::error(__FUNCTION__ . " user Exception" . $e->getMessage(), $e->getTrace());
            return response([
                'message' => 'Internal server error.',
                'code' => Response::HTTP_INTERNAL_SERVER_ERROR,
            ]);
        }
    }

    /**
     * Get authenticated user info.
     *
     * @param App\Models\User $user
     * @return mixed
     */
    public function show(User $user)
    {
        try {
            if ($user->role_id > 1) {
                return response([
                    'message' => 'Unauthorized.',
                    'code' => Response::HTTP_UNAUTHORIZED,
                ], Response::HTTP_UNAUTHORIZED);
            }

            return UserCollection::make($user);
        } catch (\Exception $e) {
            Log::error(__FUNCTION__ . " user Exception" . $e->getMessage(), $e->getTrace());
            return response([
                'message' => $e->getMessage(),
                'code' => Response::HTTP_INTERNAL_SERVER_ERROR,
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Get roles.
     *
     * @return mixed
     */
    public function roles()
    {
        try {
            return Role::where('id', '>', 1)->get();
        } catch (\Exception $e) {
            Log::error(__FUNCTION__ . " user Exception" . $e->getMessage(), $e->getTrace());
            return response([
                'message' => 'Internal server error.',
                'code' => Response::HTTP_INTERNAL_SERVER_ERROR,
            ]);
        }
    }

    private function userExist($email)
    {
        if (User::where('email', '=', $email)->count() > 0) {
            return response([
                'message' => 'Cannot register with same email.',
                'code' => Response::HTTP_UNPROCESSABLE_ENTITY,
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }
    }
}
