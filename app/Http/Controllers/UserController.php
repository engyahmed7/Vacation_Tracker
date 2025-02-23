<?php

namespace App\Http\Controllers;

use App\Http\Requests\ChangeUserPasswordRequest;
use App\Http\Requests\RegisterUserRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Http\Traits\ResponseTrait;


class UserController extends Controller
{
    use ResponseTrait;

    public function register(RegisterUserRequest $request)
    {
        try{

            $userData = $request->validated();

            if (User::where('username', $request->username)->exists()) {
                return $this->failResponse('Username already exists.');
            }

            if (isset($request->email) && User::where('email', $request->email)->exists()) {
                return $this->failResponse('Email already exists.');
            }

            $userData->password = Hash::make($userData->password);

            $user = User::create($userData);

            return $this->successResponse('User created successfully', $user, 201);

        }catch(\Exception $e){
            return $this->failResponse($e->getMessage());
        }
    }
 
    public function listAllUsers(Request $request)
{
    try {
        $pages = $request->input('pages', 1); 
        $limit = $request->input('limit', 10); 
        $totalUsers = User::count();

        $itemsPerPage = ceil($totalUsers / $pages);

        $offset = ($pages - 1) * $itemsPerPage;

        $users = User::offset($offset)->limit($limit)->get();
        return $this->successResponse('Users retrieved successfully', $users);
    } catch (\Exception $e) {
        return $this->failResponse($e->getMessage());
    }
}
     
    public function changePassword(Request $request)
    {
        try{
            $request->validated();
            $user = User::where('name', $request->name)->first();

            if(!$user){
                return $this->failResponse('User not found');
            }

            $user->password = Hash::make($request->new_password);
            $user->save();

            return $this->successResponse('Password changed successfully');

        }catch(\Exception $e){
            return $this->failResponse($e->getMessage());
        }
    }
}


