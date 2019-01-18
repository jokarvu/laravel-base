<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Auth;
use App\Category;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if(Auth::user()->can('view-category')) {
            $categories = Category::all();
            $categories->load('mother');
            return Response::json($categories);
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
        if(Auth::user()->can('create-category')) {
            $categories = Category::all();
            return Response::json($categories);
        }
        return Response::json(['message' => 'You do not have permisison'], 401);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if(Auth::user()->can('create-category')) {
            $data = $request->all();
            if(Category::create($data)) {
                return Response::json(['message' => 'Category has been created']);
            }
            return Response::json(['message' => 'Failed to create category'], 422);
        }
        return Response::json(['message' => 'You do not have permission'], 401);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($slug)
    {
        if(Auth::user()->can('view-category')) {
            $category = Category::whereSlug($slug)->firstOrFail();
            if($category) {
                $products = $category->products()->get();
                return Response::json(compact(['category', 'products']));
            }
            return Response::json(['message' => 'Category does not exist'], 404);
        }
        return Response::json(['message' => 'You do not have permission'], 401);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($slug)
    {
        if(Auth::user()->can('update-category')) {
            $category = Category::whereSlug($slug)->firstOrFail();
            if($category) {
                $categories = Category::all();
                return Response::json(compact(['category', 'categories']));
            }
            return Response::json(['message' => 'Category does not exist'], 404);
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
        if(Auth::user()->can('update-category')) {
            $category = Category::find($id);
            $data = $request->all();
            if($category) {
                if($category->update($data)) {
                    return Response::json(['message' => 'Category has been updated']);
                }
                return Response::json(['message' => 'Failed to update category'], 422);
            }
            return Response::json(['message' => 'Category does not exist'], 404);
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
        if(Auth::user()->can('delete-category')) {
            $category = Category::find($id);
            if($category) {
                if($category->delete()) {
                    return Response::json(['message' => 'Category and all products in it have been deleted']);
                }
                return Response::json(['message' => 'Failed to delete category'], 422);
            }
            return Response::json(['message' => 'Category does not exist'], 404);
        }
        return Response::json(['message' => 'You do not have permission'], 401);
    }
}
