<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\Category;
use Illuminate\Http\Request;
use App\Helper\ResponseHelper;
use Illuminate\Validation\Rule;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\File;
use Intervention\Image\Facades\Image;
use Illuminate\Database\QueryException;

class CategoryController extends Controller
{
    public function CategoryCreate(Request $request): JsonResponse
    {
        try {
            $request->validate([
                'name' => 'required|string|min:3|unique:categories',
                'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            ]);

            $category = Category::create(['name' => $request->input('name')]);

            if ($request->hasFile('image')) {
                $image = $request->file("image");
                $t = time();
                $file_name = $image->getClientOriginalName();
                $newName = "{$t}-{$file_name}";

                $directory = 'uploads/category_img';
                $path = "{$directory}/{$newName}";

                File::ensureDirectoryExists(public_path($directory));

                $img = Image::make($image);
                $img->resize(400, 350, function ($constraint) {
                    $constraint->aspectRatio();
                });
                $img->save(public_path($path));

                $image_url = asset($path);
                $category->update(['image' => $image_url]);
            }

            return ResponseHelper::Out('success',  $category, 200);
        } catch (Exception $exception) {
            $message = $exception->getMessage();
            return ResponseHelper::Out('fail',  $message, 500);
        }
    }


    function CategoryUpdate(Request $request): JsonResponse
    {
        try {
            $request->validate([
                'name' => ['required', 'string', 'min:3', Rule::unique('categories', 'name')->ignore($request->input('id'))],
                'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            ]);

            $category_id = $request->input('id');
            $category = Category::find($category_id);

            if (!$category) {
                return ResponseHelper::Out('fail', 'Category not found', 404);
            }

            if ($request->hasFile('image')) {
                $directory = 'uploads/category_img';
                $image = $request->file("image");

                // Delete the old image 
                $old_img = $category->image;
                if ($old_img) {
                    $pathParts = explode('/', $old_img);
                    $old_imgName = end($pathParts);
                    File::delete(public_path("{$directory}/{$old_imgName}"));
                }

                // Upload 
                $t = time();
                $file_name = $image->getClientOriginalName();
                $newName = "{$t}-{$file_name}";

                $path = "{$directory}/{$newName}";
                Image::make($image)->resize(400, 350, function ($constraint) {
                    $constraint->aspectRatio();
                })->save(public_path($path));

                $category->update([
                    'image' => asset($path),
                    'name' => $request->input('name')
                ]);
            } else {
                $category->update([
                    'name' => $request->input('name')
                ]);
            }
            return ResponseHelper::Out('success', ['category' => $category], 200);
        } catch (Exception $exception) {
            $message = $exception->getMessage();
            return ResponseHelper::Out('fail', $message, 500);
        }
    }


    function CategoryList(Request $request)
    {
        $categories = Category::all();
        return ResponseHelper::Out('success',  $categories, 200);
    }

    function CategoryDelete(Request $request): JsonResponse
    {
        try {
            $request->validate([
                'id' => 'required|numeric',
            ]);
            $category_id = $request->input('id');
            $category = Category::find($category_id);
            if (!$category) {
                return ResponseHelper::Out('fail', 'Category not found', 404);
            }
            $old_img = $category->image;
            if ($old_img) {
                $directory = 'uploads/category_img';
                $pathParts = explode('/', $old_img);
                $old_imgName = end($pathParts);

                File::delete(public_path("{$directory}/{$old_imgName}"));
            }

            $category->delete();
            return ResponseHelper::Out('success', null, 200);
        } catch (Exception $exception) {
            return ResponseHelper::Out('fail', $exception->getMessage(), 500);
        }
    }

    function CategoryByID(Request $request): JsonResponse
    {
        try {
            $request->validate([
                'id' => 'required|numeric',
            ]);

            $category_id = $request->input('id');
            $category = Category::find($category_id);

            if ($category) {
                return ResponseHelper::Out('success', $category, 200);
            }

            return ResponseHelper::Out('fail', 'Category not found', 404);
        } catch (Exception $exception) {
            return ResponseHelper::Out('fail', $exception->getMessage(), 500);
        }
    }
}
