<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;
use App\Helper\ResponseHelper;
use Illuminate\Support\Facades\Validator;

class CustomerController extends Controller
{
    function CustomerCreate(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:customers,email|max:255',
            'mobile' => 'required|string|unique:customers,mobile|max:15',
        ]);

        if ($validator->fails()) {
            return ResponseHelper::Out('fail', $validator->errors(), 400);
        }

        $customer = Customer::create([
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'mobile' => $request->input('mobile')
        ]);
        return ResponseHelper::Out('success', $customer, 201);
    }

    function CustomerList(Request $request)
    {
        $user_id = $request->header('id');
        if ($user_id) {
            $customers = Customer::all();
            return ResponseHelper::Out('success', $customers, 201);
        } else {
            return ResponseHelper::Out('fail', 'unauthorised', 400);
        }
    }
    function CustomerDelete(Request $request)
    {
        $customer_id = $request->input('id');
        $user_id = $request->header('id');

        if ($user_id) {
            Customer::where('id', $customer_id)->delete();
            return ResponseHelper::Out('success', '', 201);
        } else {
            return ResponseHelper::Out('fail', 'unauthorised', 400);
        }
    }

    function CustomerByID(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required|numeric',
        ]);

        if ($validator->fails()) {
            return ResponseHelper::Out('fail', $validator->errors(), 400);
        }

        $customer_id = $request->input('id');
        $user_id = $request->header('id');

        if ($user_id) {
            $customer = Customer::where('id', $customer_id)->first();
            if (!$customer) {
                return ResponseHelper::Out('fail', 'Customer not found', 404);
            }
            return ResponseHelper::Out('success', $customer, 201);
        } else {
            return ResponseHelper::Out('fail', 'unauthorised', 400);
        }
    }

    function CustomerUpdate(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required|numeric',
            'name' => 'required|string',
            'email' => 'required|email',
            'mobile' => 'required|string',
        ]);

        if ($validator->fails()) {
            return ResponseHelper::Out('fail', $validator->errors(), 400);
        }

        $customer_id = $request->input('id');
        $user_id = $request->header('id');

        if (!$user_id) {
            return ResponseHelper::Out('fail', 'Unauthorized', 401);
        }

        $customer = Customer::find($customer_id);
        if (!$customer) {
            return ResponseHelper::Out('fail', 'Customer not found', 404);
        }

        $customer->update([
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'mobile' => $request->input('mobile'),
        ]);

        return ResponseHelper::Out('success', $customer, 200);
    }
}
