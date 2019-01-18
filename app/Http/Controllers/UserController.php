<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Auth;
use App\Role;
use App\User;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if(Auth::user()->can('view-user')) {
            return Response::json(User::all()->load('role'));
        }
        return Response::json(['message' => 'You do not have permisison'], 401);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if(Auth::user()->can('create-user')) {
            $roles = Role::all();
            return Response::json($roles);
        }
        return Response::json(['message' => 'You do not have permission'], 401);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if(Auth::guest() || Auth::user()->can('create-user')) {
            $data = $request->all();
            if(!$request->filled('role_id')) {
                $data['role_id'] = Role::whereName('member')->firstOrFail()->id;
            }
            if(User::create($data)) {
                return Response::json(['message' => 'User has been added']);
            }
            return Response::json(['message' => 'Failed to create user'], 422);
        }
        return Response::json(['message' => 'You do not have permission'], 401);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($name)
    {
        if(Auth::user()->can('view-user')) {
            $user = User::whereName($name)->firstOrFail();
            $permissions = User::permissions($user->id)->get();
            if($user) {
                return Response::json(compact(['user', 'permissions']));
            }
            return Response::json(['message' => 'User does not exist'], 404);
        }
        return Response::json(['message' => 'You do not have permission'], 401);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($name)
    {
        if(Auth::user()->can('update-user')) {
            $user = User::whereName($name)->firstOrFail();
            $roles = Role::all();
            if($user) {
                $checked = $user->role()->get();
                return Response::json(compact(['user', 'roles']));
            }
            return Response::json(['message' => 'User does not exist'], 404);
        }
        return Response::json(['message' => 'You do not have permission'], 401);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        if(Auth::user()->can('update-user')) {
            $data = $request->all();
            $user = User::find($id);
            if($user) {
                if($user->update($data)) {
                    return Response::json(['message' => 'User has been updated']);
                }
                return Response::json(['message' => 'Failed to update user'], 422);
            } 
            return Response::json(['message' => 'User does not exist'], 404);
        }
        return Response::json(['message' => 'You do not have permission'], 401);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if(Auth::user()->can('delete-user')) {
            $user = User::find($id);
            if ($user) {
                if($user->delete()) {
                    return Response::json(['message' => 'User has been deleted']);
                }
                return Response::json(['message' => 'Failed to delete user'], 422);
            }
            return Response::json(['message' => 'User does not exist'], 404);
        }
        return Response::json(['message' => 'You do not have permission'], 401);
    }
}
