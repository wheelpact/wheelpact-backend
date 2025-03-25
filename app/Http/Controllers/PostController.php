<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Http\Controllers\Api\ApiController;
use App\Http\Requests\Post\StorePostRequest;
use App\Http\Requests\Post\UpdatePostRequest;

class PostController extends ApiController {
    /**
     * Display a listing of the resource.
     */
    public function index() {
        $posts = Post::get();
        return response()->json([
            'message' => 'Posts retrieved successfully',
            'posts' => $posts
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePostRequest $request) {
        $post = Post::create($request->validated());
        return $this->successResponse('Post created successfully', $post, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Post $post) {
        return $this->successResponse('Post retrieved successfully', $post, 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePostRequest $request, Post $post) {

        $post->validated();
        if ($post->update($request->validated())) {
            return $this->successResponse('Post updated successfully', $post, 200);
        }
        return $this->internalServerErrorResponse('Post not updated, please try again');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Post $post) {

        if ($post->delete()) {
            return $this->successResponse('Post deleted successfully', null, 200);
        }
        return $this->internalServerErrorResponse('Post not deleted, please try again');
    }
}
