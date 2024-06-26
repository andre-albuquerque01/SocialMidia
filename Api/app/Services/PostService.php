<?php

namespace App\Services;

use App\Exceptions\PostException;
use App\Http\Resources\GeneralResource;
use App\Http\Resources\PostResource;
use App\Models\Posts;
use Exception;
use Illuminate\Support\Facades\Storage;

class PostService
{

    public function index()
    {
        try {
            $post = Posts::with(['comments' => function ($query) {
                $query->whereNull("comments.deleted_at");
            }])
                ->with('rates')
                ->whereNull("posts.deleted_at")
                ->latest('posts.updated_at')
                ->paginate(100);
            return PostResource::collection($post);
        } catch (Exception $th) {
            throw new PostException('Error to list post');
        }
    }

    public function store(array $data)
    {
        try {
            if (isset($data['imageUrlOne'])) {
                $image = $data['imageUrlOne'];
                if ($image->getClientOriginalExtension() != null) {
                    $newName_image = uniqid() . "." . $image->getClientOriginalExtension();
                    Storage::disk('public')->put('img/post/' . $newName_image, file_get_contents($image));
                    $data['imageUrlOne'] = $newName_image;
                } else {
                    $data['imageUrlOne'] = null;
                }
            } else {
                $data['imageUrlOne'] = null;
            }

            auth()->user()->posts()->create($data);

            return new GeneralResource(['message' => 'success']);
        } catch (Exception $th) {
            throw new PostException('Error creating post');
        }
    }

    public function showUser()
    {
        try {
            $show = auth()->user()->posts()
                ->with(['comments' => function ($query) {
                    $query->whereNull("comments.deleted_at");
                }])
                ->with('rates')
                ->whereNull("posts.deleted_at")
                ->paginate(100);
            return PostResource::collection($show);
        } catch (Exception $th) {
            throw new PostException('Error creating post');
        }
    }

    public function showPostUser(string $idUser)
    {
        try {
            $post = Posts::where('posts.user_idUser', '=', $idUser)
                ->with(['comments' => function ($query) {
                    $query->whereNull("comments.deleted_at");
                }])
                ->with('rates')
                ->whereNull("posts.deleted_at")
                ->orderBy('posts.updated_at', 'DESC')
                ->paginate(100);
            return PostResource::collection($post);
        } catch (Exception $th) {
            throw new PostException('Error creating post');
        }
    }

    public function update(array $data, string $idPost)
    {
        try {
            $user = auth()->user()->idUser;
            $post = Posts::where('idPost', $idPost)->where('user_idUser', $user)->first();

            if (!$post) {
                return new GeneralResource(["message" => "Unathorized"]);
            }

            if (isset($data['imageUrlOne'])) {
                $image = $data['imageUrlOne'];
                if ($image->getClientOriginalExtension() != null) {
                    $newName_image = uniqid() . "." . $image->getClientOriginalExtension();
                    Storage::disk('public')->put('img/post/' . $newName_image, file_get_contents($image));
                    $data['imageUrlOne'] = $newName_image;
                } else {
                    $data['imageUrlOne'] = $post->imageUrlOne;
                }
            } else {
                $data['imageUrlOne'] = null;
            }

            $post->update($data);

            return new GeneralResource(['message' => 'success']);
        } catch (Exception $th) {
            throw new PostException('Error update post');
        }
    }

    public function destroy(string $idPost)
    {
        try {
            $user = auth()->user()->idUser;

            $post = Posts::where('idPost', $idPost)->where('user_idUser', $user)->first();

            if (!$post) {
                return new GeneralResource(["message" => "Unathorized"]);
            }

            $post->touch("deleted_at");
            return response()->json(['message' => 'success'], 200);
        } catch (Exception $th) {
            throw new PostException('Error destroy post');
        }
    }
}
