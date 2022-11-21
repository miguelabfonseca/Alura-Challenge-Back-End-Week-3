<?php

namespace App\Http\Controllers;

use App\Models\Video;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\JsonResponse;

class CategoriesController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login']]);
    }

    public function index(): JsonResponse
    {
        $result = Category::get();
        return response()->json(['categories' => $result->toArray(), 'count' => $result->count(), 'status' => 'ok', 'message' => ''] , 200);
    }

    public function show($id): JsonResponse
    {
        $category = Category::find($id);
        if(!$category) {
            return response()->json(['status' => 'error', 'message' => 'Category not found!'], 404);
        }
        return response()->json(['category' => [$category->toArray()], 'status' => 'ok', 'message' => ''], 200);
    }

    public function store(Request $request): JsonResponse
    {
        $validator = Validator::make(
            [
                'title'  =>  $request->title,
                'color' => $request->color,
            ],
            [
                'title'  => 'required|min:1',
                'color' => 'required|min:1',
            ]
        );

        if ($validator->fails()) {
            $errorMessage = '';
            foreach($validator->errors()->all() as $message) {
                $errorMessage .= $message . '; ';
            }
            return response()->json(['status' => 'error', 'message' => $errorMessage], 206);
        }

        $category = Category::create([
            'title' => $request->title,
            'color' => $request->color
        ]);
        return response()->json(["category" => [$category->toArray()], "status" => "ok", "message" => "Created successfully"], 201);
    }

    public function update(Request $request, $id): JsonResponse
    {
        $category = Category::find($id);
        if(!$category) {
            return response()->json(['status' => 'error', 'message' => 'Category not found!'], 404);
        }
        $validator = Validator::make(
            [
                'title'  =>  $request->title,
                'color' => $request->color
            ],
            [
                'title'  => 'required|min:1',
                'color' => 'required|min:1',
            ]
        );

        if ($validator->fails()) {
            $errorMessage = '';
            foreach($validator->errors()->all() as $message) {
                $errorMessage .= $message . '; ';
            }
            return response()->json(['status' => 'error', 'message' => $errorMessage], 206);
        }
        $category->fill($request->all());
        $category->save();

        return response()->json(['category' => $category->toArray(), "status" => "ok", "message" => "Updated successfully"], 200);
    }

    public function destroy($id): JsonResponse
    {
        $category = Category::find($id);
        if(!$category) {
            return response()->json(['status' => 'error', 'message' => 'Category not found!'], 404);
        }
        $category->delete();
        return response()->json(['status' => 'ok', 'message' => 'Category deleted with success!'], 200);
    }

    public function videos($id)
    {
        $videos = Video::where('category', $id)->with('category')->get();
        if($videos->count() == 0)
        {
            return response()->json(['status' => 'error', 'message' => 'No videos found on the selected category!'], 404);
        }
        return response()->json(['videos' => $videos->toArray(), 'count' => $videos->count(), 'status' => 'ok', 'message' => ''], 200);
    }



}
