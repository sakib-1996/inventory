<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\Product;
use Illuminate\Http\Request;
use App\Helper\ResponseHelper;
use Illuminate\Support\Facades\File;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller
{
    public function CreateProduct(Request $request)
    {
        try {
            // Validate the incoming request data
            $validator = Validator::make($request->all(), [
                'category_id' => 'required|exists:categories,id',
                'name' => 'required|string|max:100',
                'price' => 'required|string|max:50',
                'unit' => 'required|string|max:50',
                'qty' => 'required|numeric',
                'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            ]);

            // Check if validation fails and return errors if any
            if ($validator->fails()) {
                return ResponseHelper::Out('fail', $validator->errors(), 422);
            }

            // Create a new product
            $product = Product::create([
                'category_id' => $request->input('category_id'),
                'name' => $request->input('name'),
                'user_id' => $request->header('id'),
                'total_qty' => $request->input('qty'),
                'price' => $request->input('price'),
                'unit' => $request->input('unit'),
                'qty' => $request->input('qty'),
            ]);

            // Handle image upload if provided
            if ($request->hasFile('image')) {
                $image = $request->file("image");
                $t = time();
                $file_name = $image->getClientOriginalName();
                $newName = "{$t}-{$file_name}";

                $directory = 'uploads/product_img';
                $path = "{$directory}/{$newName}";

                File::ensureDirectoryExists(public_path($directory));

                $img = Image::make($image);
                $img->resize(400, 350, function ($constraint) {
                    $constraint->aspectRatio();
                });
                $img->save(public_path($path));

                $image_url = asset($path);
                $product->update(['img_url' => $image_url]);
            }

            // Return success response with the created product
            return ResponseHelper::Out('success', $product, 200);
        } catch (Exception $exception) {
            // Return fail response with the exception message
            $message = $exception->getMessage();
            return ResponseHelper::Out('fail', $message, 500);
        }
    }


    public function UpdateProduct(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'id' => 'required|exists:products,id',
                'category_id' => 'required|exists:categories,id',
                'name' => 'required|string|max:100',
                'price' => 'required|string|max:50',
                'unit' => 'required|string|max:50',
                'qty' => 'required|numeric',
                'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            ]);

            $user_id = $request->header('id');
            $product_id = $request->input('id');

            $product = Product::where('id', $product_id)->where('user_id', $user_id)->first();

            if (!$product) {
                return ResponseHelper::Out('fail', 'Product not found or unauthorized', 404);
            }

            if ($request->hasFile('image')) {
                $directory = 'uploads/product_img';
                $image = $request->file("image");

                // Delete the old image 
                $old_img = $product->img_url;
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

                $product->update([
                    'img_url' => asset($path),
                    'category_id' => $request->input('category_id'),
                    'name' => $request->input('name'),
                    'total_qty' => $request->input('qty'),
                    'price' => $request->input('price'),
                    'unit' => $request->input('unit'),
                    'qty' => $request->input('qty'),
                ]);
            } else {
                // Update without image
                $product->update([
                    'category_id' => $request->input('category_id'),
                    'name' => $request->input('name'),
                    'total_qty' => $request->input('qty'),
                    'price' => $request->input('price'),
                    'unit' => $request->input('unit'),
                    'qty' => $request->input('qty'),
                ]);
            }

            return ResponseHelper::Out('success', $product, 200);
        } catch (Exception $exception) {
            $message = $exception->getMessage();
            return ResponseHelper::Out('fail', $message, 500);
        }
    }

    function DeleteProduct(Request $request)
    {
        try {
            $request->validate([
                'id' => 'required|numeric',
            ]);
            $user_id = $request->header('id');

            if (!$user_id) {
                return ResponseHelper::Out('fail', 'User ID not provided in the header', 400);
            }

            $product_id = $request->input('id');
            $product = Product::where('user_id', $user_id)->find($product_id);

            if (!$product) {
                return ResponseHelper::Out('fail', 'Product not found or unauthorized', 404);
            }

            $old_img = $product->img_url;

            if ($old_img) {
                $directory = 'uploads/product_img';
                $pathParts = explode('/', $old_img);
                $old_imgName = end($pathParts);

                File::delete(public_path("{$directory}/{$old_imgName}"));
            }

            $product->delete();

            return ResponseHelper::Out('success', null, 200);
        } catch (Exception $exception) {
            return ResponseHelper::Out('fail', $exception->getMessage(), 500);
        }
    }


    function ProductByID(Request $request)
    {
        try {
            $request->validate([
                'id' => 'required|numeric',
            ]);

            $user_id = $request->header('id');

            if (!$user_id) {
                return ResponseHelper::Out('fail', 'User ID not provided in the header', 400);
            }

            $product_id = $request->input('id');

            $product = Product::where('user_id', $user_id)->findOrFail($product_id);

            return ResponseHelper::Out('success', $product, 200);
        } catch (Exception $exception) {
            return ResponseHelper::Out('fail', $exception->getMessage(), 500);
        }
    }

    function ProductList(Request $request)
    {
        try {
            $userId = $request->header('id');

            if (!$userId) {
                return ResponseHelper::Out('fail', 'User ID not provided in the header', 400);
            }
            $products = Product::where('user_id', $userId)->get();

            return ResponseHelper::Out('success', $products, 200);
        } catch (Exception $exception) {
            $message = $exception->getMessage();
            return ResponseHelper::Out('fail', $message, 500);
        }
    }
}
