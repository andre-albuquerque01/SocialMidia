<?php

namespace App\Services;

use App\Exceptions\CommentException;
use App\Http\Resources\CommentResource;
use App\Http\Resources\GeneralResource;
use App\Models\Comments;

class CommentService
{
    public function show(string $id)
    {
        try {
            $user = auth()->user()->idUser;

            $var = Comments::where('post_idPost', $id)->where('user_idUser', $user)->get();

            if (!$var) {
                return new GeneralResource(["message" => "Not found."]);
            }

            return new CommentResource($var);
        } catch (\Throwable $e) {
            throw new CommentException("Error show");
        }
    }
    public function store(array $data)
    {
        try {

            auth()->user()->comments()->create($data);

            return new GeneralResource(['message' => 'success']);
        } catch (\Throwable $e) {
            throw new CommentException("Error store");
        }
    }
    public function update(array $data, string $id)
    {
        try {
            $user = auth()->user()->idUser;

            $var = Comments::where('post_idPost', $id)->where('user_idUser', $user)->first();

            if (!$var) {
                return new GeneralResource(["message" => "Unathorized"]);
            }

            $var->update($data);

            return new GeneralResource(['message' => 'success']);
        } catch (\Throwable $e) {
            throw new CommentException("Error updating");
        }
    }
    public function destroy(string $id)
    {
        try {
            $user = auth()->user()->idUser;
            $var = Comments::where('idComment', $id)->where('user_idUser', $user)->first();
            if (!$var) {
                return new GeneralResource(["message" => "Unathorized"]);
            }

            $var->touch('deleted_at'); 
            return new GeneralResource(['message' => 'success']);
        } catch (\Throwable $e) {
            throw new CommentException("Error destroying");
        }
    }
}
