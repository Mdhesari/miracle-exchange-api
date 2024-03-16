<?php

namespace Modules\Comment\Http\Controllers;

use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Validation\ValidationException;
use Modules\Comment\Actions\ApplyCommentQueryFilters;
use Modules\Comment\Actions\CreateComment;
use Modules\Comment\Actions\GetCommentableType;
use Modules\Comment\Actions\UpdateComment;
use Modules\Comment\Entities\Comment;
use Modules\Comment\Http\Requests\CommentRequest;

class CommentController extends Controller
{
    use AuthorizesRequests;

    /**
     * @param Request $request
     * @param string $type
     * @param ApplyCommentQueryFilters $applyCommentQueryFilters
     * @param GetCommentableType $getCommentableType
     * @return JsonResponse
     * @throws ValidationException
     * @throws \Exception
     * @LRDparam s string
     * @LRDparam commentable_id integer
     * @LRDparam user_id integer
     * @LRDparam oldest boolean
     * @LRDparam date_from integer
     * @LRDparam date_to integer
     */
    public function index(Request $request, string $type, ApplyCommentQueryFilters $applyCommentQueryFilters, GetCommentableType $getCommentableType): JsonResponse
    {
        $commentable_type = $getCommentableType($type);

        $query = Comment::query()->where('commentable_type', $commentable_type);

        if ($request->has('commentable_id')) {
            $query->where('commentable_id', $request->get('commentable_id'));
        }

        $query = $applyCommentQueryFilters($query, $request->all());

        return api()->success(null, [
            'items' => $query->paginate(),
        ]);
    }

    /**
     * @param Request $request
     * @param string $type
     * @param $comment
     * @param GetCommentableType $getCommentableType
     * @return JsonResponse
     * @throws AuthorizationException
     * @throws \Exception
     */
    public function show(Request $request, string $type, $comment, GetCommentableType $getCommentableType): JsonResponse
    {
        $commentable_type = $getCommentableType($type);

        $comment = Comment::where('commentable_type', $commentable_type)->where('id', $comment)->firstOrFail();

        $this->authorize('show', $comment);

        return api()->success(null, [
            'item' => $comment,
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param string $type
     * @param $comment
     * @param GetCommentableType $getCommentableType
     * @return JsonResponse
     * @throws AuthorizationException
     * @throws \Exception
     */
    public function destroy(string $type, $comment, GetCommentableType $getCommentableType): JsonResponse
    {
        $commentable_type = $getCommentableType($type);

        $comment = Comment::where('commentable_type', $commentable_type)->where('id', $comment)->firstOrFail();

        $this->authorize('delete', $comment);

        $comment->delete();

        return api()->success(null, []);
    }

    /**
     * @param CommentRequest $request
     * @param string $type
     * @param GetCommentableType $getCommentableType
     * @param CreateComment $createComment
     * @return JsonResponse
     * @throws \Exception
     */
    public function store(CommentRequest $request, string $type, GetCommentableType $getCommentableType, CreateComment $createComment): JsonResponse
    {
        $commentable_type = $getCommentableType($type);

        $comment = $createComment(array_replace($request->validated(), [
            'commentable_type' => $commentable_type,
        ]));

        return api()->success(null, [
            'item' => $comment,
        ]);
    }

    /**
     * @param Request $request
     * @param string $type
     * @param $comment
     * @param GetCommentableType $getCommentableType
     * @param UpdateComment $updateComment
     * @return JsonResponse
     * @throws \Exception
     */
    public function update(CommentRequest $request, string $type, $comment, GetCommentableType $getCommentableType, UpdateComment $updateComment): JsonResponse
    {
        $commentable_type = $getCommentableType($type);

        $comment = Comment::where('commentable_type', $commentable_type)->where('id', $comment)->firstOrFail();

        $this->authorize('update', $comment);

        $comment = $updateComment($comment, $request->validated());

        return api()->success(null, [
            'item' => $comment,
        ]);
    }
}
