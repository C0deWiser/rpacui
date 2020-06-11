<?php

namespace App\Http\Controllers\RpacUI;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class RoleController extends Controller
{
    protected $model;
    protected $paginate = 10;

    public function __construct()
    {
        $this->model = str_replace("\User", "\Role", config('rpac.models.user'));
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //return $this->model::get();
        return $this->model::query()
                    //->search($request->input('q') ?? '')
                    ->paginate($request->paginate ?? $this->paginate);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        return $this->model::create($request->input());
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {

        $role = $this->model::findOrFail($id);
        return $role;
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
        $role = $this->model::findOrFail($id);
        $role->update($request->input());
        return $role;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $role = $this->model::findOrFail($id);
        $role->delete();
        return $role;
    }
}
