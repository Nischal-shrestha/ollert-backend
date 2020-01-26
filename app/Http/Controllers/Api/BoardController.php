<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\CreateBoardRequest;
use App\Models\Board\Board;
use App\Http\Resources\Board\Board as BoardResource;
use App\Http\Resources\Board\BoardCollection;
use App\Traits\ResponseHelper;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

/**
 * Class BoardController
 * @package App\Http\Controllers\Api
 */
class BoardController extends Controller
{
    use ResponseHelper;

    /**
     * Display a listing of the resource.
     *
     * @return BoardCollection
     */
    public function index()
    {
        $user = Auth::user();

        $boards = $user->boards;

        return new BoardCollection($boards);

//        return response()->json($boards, 200);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param Request $request
     * @return void
     */
    public function create(Request $request)
    {

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param CreateBoardRequest $request
     * @return JsonResponse
     */
    public function store(CreateBoardRequest $request)
    {
        $user = Auth::user();

        //name, description, visibility, background
        $validated = $request->validated();
        $response = null;
        $newBoard = new Board($request->only('name', 'description', 'visibility', 'background'));
        if ($user->ownedBoards()->save($newBoard)) {
            if ($newBoard->visibility != Board::PRIVATE) {
                $newBoard->users()->attach($user, ['is_owner' => true]);
            }
            $this->composeStatus($response, 'created', 'The board has been created');
            return response()->json($response, 201);
        } else {
            $this->composeStatus($response, 'failed', 'Failed to create the board!');
        }


    }

    /**
     * Display the specified resource.
     *
     * @param Board $board
     * @return BoardResource
     */
    public function show(Board $board)
    {
        if (Gate::allows('view-board', $board)) {
            return new BoardResource($board);
        } else {
            return response()->json([
                'error' => 'unauthorized_action',
                'message' => 'You do not have access to this board',
            ], 403);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return void
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param int $id
     * @return void
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Request $request
     * @param Board $board
     * @return void
     */
    public function destroy(Request $request, Board $board)
    {
        //soft delete the board
    }
}
