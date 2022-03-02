<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Image;
use App\Models\TodoList;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;

class TodoListController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try {
            return TodoList::all();
        } catch (Exception $e) {
            return response($e->getMessage(), $e->getCode());
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try {

            DB::transaction(function () use ($request) {
                $todoList = new TodoList([
                    'title' => $request->title,
                    'content' => $request->content,
                    'created_by' => $request->created_by,
                ]);
                $todoList->save();
            });
            return response('Create Success', Response::HTTP_CREATED);
        } catch (Exception $e) {
            DB::rollBack();
            return response($e->getMessage(), Response::HTTP_UNPROCESSABLE_ENTITY);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(TodoList $todoList)
    {
        return $todoList;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, TodoList $todoList)
    {
        try {
            DB::transaction(function () use ($request, $todoList) {
                $todoList->title = $request->title;
                $todoList->content = $request->content;
                $todoList->created_by = $request->created_by;
                $todoList->save();
                return response('Update Success', Response::HTTP_OK);
            });
        } catch (Exception $e) {
            return response($e->getMessage(), $e->getCode());
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(TodoList $todoList)
    {
        try {
            DB::transaction(function () use ($todoList) {
                $todoList->delete();
                return response('Update Success', Response::HTTP_OK);
            });
        } catch (Exception $e) {
            return response($e->getMessage(), $e->getCode());
        }
    }

    public function upload(Request $request)
    {
        try {
            DB::transaction(function () use ($request) {
                if ($request->hasFile('images')) {
                    foreach ($request->file('images') as $file) {
                        $name = $file->getClientOriginalName();
                        $path = $file->store('images');
                        $image = new Image([
                            'name' => $name,
                            'file_path' => $path,
                            'todolist_id' => $request->todolist_id
                        ]);
                        $image->save();
                    }
                } else
                    throw new Exception('Upload image had no file.', Response::HTTP_UNPROCESSABLE_ENTITY);
            });
            return TodoList::find($request->todolist_id)->images;
        } catch (Exception $e) {
            response($e->getMessage(), $e->getCode());
        }
    }
}
