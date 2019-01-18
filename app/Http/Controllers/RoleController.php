<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Auth;
use App\Role;
use App\Permission;

class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if(Auth::user()->can('view-role')) {
            return Response::json(Role::all());
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
        if(Auth::user()->can('create-role')) {
            $permissions = Permission::all();
            return Response::json($permissions);
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
        if(Auth::user()->can('create-role')) {
            $data = $request->except('checked');
            $permissions = $request->input('checked');
            if($role = Role::create($data)) {
                $role->permissions()->attach($permissions);
                return Response::json(['message' => 'Role has been created']);
            }
            return Response::json(['message' => 'Failed to create new role'], 422);
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
        if(Auth::user()->can('view-role')) {
            $role = Role::whereName($name)->firstOrFail();
            $permissions = $role->permissions()->get();
            $users = $role->users()->get();
            if($role) {
                return Response::json(compact(['permissions', 'role', 'users']));
            }
            return Response::json(['message' => 'Role does not exist'], 404);
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
        if (Auth::user()->can('view-role')) {
            $role = Role::whereName($name)->firstOrFail();
            $permissions = Permission::orderBy('name', 'asc')->get();
            $checked = $role->permissions()->select('permissions.id')->get();
            return Response::json(compact(['role', 'permissions', 'checked']));
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
        if(Auth::user()->can('update-role')) {
            $data = $request->except('permisisons');
            $permissions = $request->input('permissions');
            $role = Role::find($id);
            if($role) {
                if($role->update($data) && $role->permissions()->sync($permissions)) {
                    return Response::json(['message' => 'Role has been updated']);
                }
                return Response::json(['message' => 'Failed to update role'], 422);
            }
            return Response::json(['message' => 'Role does not exist'], 404);
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
        if(Auth::user()->can('delete-role')) {
            $role = Role::find($id);
            if($role && $role->delete()) {
                return Response::json(['message' => "Role deleted"]);
            }
            return Response::json(['message' => 'Role does not exist'], 404);
        }
        return Response::json(['message' => 'You do not have permission'], 401);
    }
}
