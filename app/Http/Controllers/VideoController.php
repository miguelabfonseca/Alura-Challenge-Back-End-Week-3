<?php

namespace App\Http\Controllers;

use App\Models\Video;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\JsonResponse;

class VideoController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login']]);
    }

    public function index(Request $request): JsonResponse
    {
        if ($request->search) {
            $result = Video::where('title', 'like', '%' . $request->search . '%')
                ->where('description', 'like', '%' . $request->search . '%')
                ->get();
            if ($result->count() == 0) {
                return response()->json(['status' => 'error', 'message' => 'No video was found with the search string!'], 404);
            }

        } else {
            $result = Video::get();
        }
        return response()->json(['videos' => $result->toArray(), 'count' => $result->count(), 'status' => 'ok', 'message' => ''], 200);
    }

    public function show($id): JsonResponse
    {
        $video = Video::find($id);
        if (!$video) {
            return response()->json(['status' => 'error', 'message' => 'Video not found!'], 404);
        }
        return response()->json(['video' => [$video->toArray()], 'status' => 'ok', 'message' => ''], 200);
    }

    public function store(Request $request): JsonResponse
    {
        $validator = Validator::make(
            [
                'title' => $request->title,
                'description' => $request->description,
                'url' => $request->url,
            ],
            [
                'title' => 'required|min:1',
                'description' => 'required|min:1',
                'url' => 'required|url',
            ]
        );

        if ($validator->fails()) {
            $errorMessage = '';
            foreach ($validator->errors()->all() as $message) {
                $errorMessage .= $message . '; ';
            }
            return response()->json(['status' => 'error', 'message' => $errorMessage], 206);
        }

        $video = Video::create([
            'title' => $request->title,
            'category' => $request->category,
            'description' => $request->description,
            'url' => $request->url,
        ]);
        return response()->json(['video' => [$video->toArray()], 'status' => 'ok', 'message' => "Video created"], 201);
    }

    public function update(Request $request, $id): JsonResponse
    {
        $video = Video::find($id);
        if (!$video) {
            return response()->json(['status' => 'error', 'message' => 'Video not found!'], 404);
        }
        if($request->title) {
            $post['title'] = $request->title;
        } else {
            $post['title'] = $video->title;
        }
        if($request->description) {
            $post['description'] = $request->description;
        } else {
            $post['description'] = $video->description;
        }
        if($request->url) {
            $post['url'] = $request->url;
        } else {
            $post['url'] = $video->url;
        }

        $video->fill($post);
        $video->save();

        return response()->json(['video' => $video->toArray(), 'status' => 'ok', 'message' => "Video updated"], 200);
    }

    public function destroy($id): JsonResponse
    {
        $video = Video::find($id);
        if (!$video) {
            return response()->json(['status' => 'error', 'message' => 'Video not found!'], 404);
        }
        $video->delete();
        return response()->json(['status' => 'ok', 'message' => 'Video deleted with success!'], 200);
    }

    private function search($search)
    {
        $video = Video::where('title', 'like', $search)
            ->orWhere('description', 'like', $search)
            ->get();

        if ($video->count() == 0) {
            return response()->json(['status' => 'error', 'message' => 'No video was found with the search string!'], 404);
        }
        return response()->json(['video' => $video->toArray(), 'status' => 'ok', 'message' => "Video found"], 200);
    }

}
