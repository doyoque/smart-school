<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Models\Message;
use App\Events\MessageEvent;

class ChatController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show chats.
     *
     * @return Illuminate\Http\Response $response
     */
    public function fetchMessages()
    {
        return response([
            'data' => Message::with('user')->get(),
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
            ]);

            broadcast(new MessageEvent($user, $message))->toOthers();

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
