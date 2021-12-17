<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Models\User;
use App\Models\Message;
use App\Events\MessageEvent;
use App\Http\Resources\UserCollection;

class ChatController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * List available user.
     *
     * @param Illuminate\Http\Request $request
     * @return mixed
     */
    public function user(Request $request)
    {
        try {
            $users = User::where('role_id', '>', 1)
                ->where('name', '!=', $request->user()->name)
                ->where('school_id', '=', $request->user()->school_id)
                ->get();
            return UserCollection::collection($users);
        } catch (\Exception $e) {
            Log::error(__FUNCTION__ . " user Exception" . $e->getMessage(), $e->getTrace());
            return response(['message' => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Show chats.
     *
     * @param Illuminate\Http\Request $request
     * @return Illuminate\Http\Response $response
     */
    public function fetchMessages(Request $request)
    {
        $authUser = $request->user();

        $messages = Message::with('user')
            ->where(['user_id' => $authUser->id, 'receiver_id' => $request->query('receiver_id')])
            ->orWhere(function ($query) use ($authUser, $request) {
                $query->where(['user_id' => $request->query('receiver_id'), 'receiver_id' => $authUser->id]);
            })
            ->orderBy('created_at', 'DESC')
            ->get();
        return response([
            'data' => $messages,
            'code' => Response::HTTP_OK,
        ], Response::HTTP_OK);
    }

    /**
     * Persist message to database.
     *
     * @param Illuminate\Http\Request $request
     * @return mixed
     */
    public function sendMessage(Request $request)
    {
        try {
            $user = Auth::user();

            $message = $user->messages()->create([
                'message' => $request->input('message'),
                'receiver_id' => $request->input('receiver_id'),
            ]);

            broadcast(new MessageEvent($user, $message->load('user')))->toOthers();

            return response([
                'message' => 'Message sent.',
                'code' => Response::HTTP_OK,
            ], Response::HTTP_OK);
        } catch (\Exception $e) {
            Log::error(__FUNCTION__ . " user Exception" . $e->getMessage(), $e->getTrace());
            return response(['message' => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
