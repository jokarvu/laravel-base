<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Auth;
use Illuminate\Support\Facades\Response;
use App\Permission;
use App\Role;

class PermissionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if(Auth::user()->can('view-permission')) {
            return Response::json(Permission::all());
        }
        return Response::json(['message' => 'You do not have permission'], 401);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if(Auth::user()->can('create-permission')) {
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
        if(Auth::user()->can('create-permission')) {
            $data = $request->except('checked');
            $roles = $request->input('checked');
            if($permission = Permission::create($data)) {
                $permission->roles()->attach($roles);
                return Response::json(['message' => 'Permission has been created']);
            }
            return Response::json(['message' => 'Failed to create new permission'], 422);
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
        if(Auth::user()->can('view-permission')) {
            $permission = Permission::whereName($name)->firstOrFail();
            $roles = $permission->roles()->get();
            $users = Permission::users($permission->id)->get();
            if($permission) {
                return Response::json(compact(['permission', 'roles', 'users']));
            }
            return Response::json(['message' => 'Permission does not exist'], 404);
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
        if (Auth::user()->can('view-permission')) {
            $permission = Permission::whereName($name)->firstOrFail();
            $roles = Role::all();
            $checked = $permission->roles()->select('roles.id')->get();
            return Response::json(compact(['permission', 'roles', 'checked']));
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
        if(Auth::user()->can('update-permission')) {
            $data = $request->except('checked');
            $roles = $request->input('checked');
            $permission = Permission::find($id);
            if($permission) {
                if($permission->update($data) && $permission->roles()->sync($roles)) {
                    return Response::json(['message' => 'Permission has been updated']);
                }
                return Response::json(['message' => 'Failed to update permission'], 422);
            }
            return Response::json(['message' => 'Permission does not exist'], 404);
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
        if(Auth::user()->can('delete-permission')) {
            $permission = Permission::find($id);
            if($permission && $permission->delete()) {
                return Response::json(['message' => "Permission deleted"]);
            }
            return Response::json(['message' => 'Permission does not exist'], 404);
        }
        return Response::json(['message' => 'You do not have permission'], 401);
    }
}
