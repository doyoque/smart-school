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
use App\Jobs\ProcessInvitationEmail;

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
            $users = User::search($request->query(), $request->user()->school_id)->paginate(10);
            return UserCollection::collection($users);
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

            if (
                User::email($postData['email'], $request->user()->school_id)->count() > 0 ||
                User::email($postData['email'])->count() === 1
            ) {
                return $this->unprocessable('CREATE');
            }

            $user = User::create([
                'name' => $postData['name'],
                'username' => $postData['username'],
                'email' => $postData['email'],
                'role_id' => $postData['role_id'],
                'school_id' => $request->user()->school_id,
                'password' => Hash::make($postData['password']),
            ]);

            dispatch(new ProcessInvitationEmail([
                'receiver' => $user->email,
                'name' => $user->name,
            ]));

            return response([
                'message' => 'User created.',
                'code' => Response::HTTP_CREATED,
            ], Response::HTTP_CREATED);
        } catch (\Exception $e) {
            Log::error(__FUNCTION__ . " user Exception" . $e->getMessage(), $e->getTrace());
            return $this->unprocessable('ERROR');
        }
    }

    /**
     * Get authenticated user info.
     *
     * @param App\Models\User $user
     * @param Illuminate\Http\Request $request
     * @return mixed
     */
    public function show(Request $request, User $user)
    {
        try {
            if (
                $request->user()->roie_id > 1 ||
                ($request->user()->school_id !== $user->school_id)
            ) {
                $this->unprocessable('SHOW');
            }

            return UserCollection::make($user);
        } catch (\Exception $e) {
            Log::error(__FUNCTION__ . " user Exception" . $e->getMessage(), $e->getTrace());
            $this->unprocessable('ERROR');
        }
    }

    /**
     * Update user.
     *
     * @param App\Http\Requests\UserRequest $request
     * @param App\Models\User $user
     * @return mixed
     */
    public function update(UserRequest $request, User $user)
    {
        try {
            $postData = $request->validated();

            if (User::username($postData['username'], $user->school_id)->count() > 0) {
                $this->unprocessable('UPDATE');
            }

            $user->name = $postData['name'];
            $user->username = $postData['username'];
            $user->role_id = $postData['role_id'];

            if ($user->update()) {
                return response([
                    'message' => 'User has been update.',
                    'code' => Response::HTTP_OK,
                ], Response::HTTP_OK);
            }
        } catch (\Exception $e) {
            Log::error(__FUNCTION__ . " user Exception" . $e->getMessage(), $e->getTrace());
            $this->unprocessable('ERROR');
        }
    }

    /**
     * Delete user.
     *
     * @param Illuminate\Http\Request $request
     * @param App\Models\User $user
     * @return mixed
     */
    public function delete(Request $request, User $user)
    {
        try {
            if ($request->user()->school_id !== $user->school_id) {
                return $this->unprocessable('DELETE');
            }

            if ($user->delete()) {
                return response([
                    'message' => 'User delete.',
                    'code' => Response::HTTP_OK,
                ], Response::HTTP_OK);
            }
        } catch (\Exception $e) {
            Log::error(__FUNCTION__ . " user Exception" . $e->getMessage(), $e->getTrace());
            $this->unprocessable('ERROR');
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
            $this->unprocessable('ERROR');
        }
    }

    /**
     * Response collection.
     *
     * @param string $method
     * @return mixed
     */
    private function unprocessable($method = null)
    {
        $data['code'] = Response::HTTP_UNPROCESSABLE_ENTITY;
        switch ($method) {
            case 'UPDATE':
                $data['message'] = 'username already use.';
                return response($data, $data['code']);
            case 'CREATE':
                $data['message'] = 'Email already in use.';
                return response($data, $data['code']);
            case 'SHOW':
                $data['message'] = 'Unauthorized.';
                return response($data, $data['code']);
            case 'DELETE':
                $data['message'] = 'Cannot delete user from different school.';
                return response($data, $data['code']);
            case 'ERROR':
                $data['code'] = Response::HTTP_INTERNAL_SERVER_ERROR;
                $data['message'] = 'Internal server error.';
                return response($data, $data['code']);
        }
    }
}
