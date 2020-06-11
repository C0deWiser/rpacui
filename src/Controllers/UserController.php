<?php

namespace App\Http\Controllers\RpacUI;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class UserController extends Controller
{

    protected $model;
    protected $paginate = 10;

    public function __construct()
    {
        $this->model = config('rpac.models.user');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        return $this->model::query()
                    ->with('roles')
                    ->search($request->input('q') ?? '')
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
        // TODO validate userStoreRequest

        $data = $request->input();
        $roles = []; // TODO default
        if(isset($data['_method'])) unset($data['_method']);
        if(isset($data['roles'])) {
            $roles = array_column($data['roles'], 'id');//collect($request->input('roles'))->pluck('id');//
            unset($data['roles']);
        }

        $password = $data['password'] ?? bin2hex(random_bytes(4)); // Str::random(8);
        $data['password'] = bcrypt($password);

        $user = $this->model::create($data);
        if(count($roles)) $user->roles()->attach($roles);

        // TODO event -> mail -> password

        return $this->show($user);
    }

    /**
     * Display the specified resource.
     *
     * @param  \Illuminate\Foundation\Auth\User  $user
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        $user = $this->model::findOrFail($user->id);
        return $user->load('roles');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Illuminate\Foundation\Auth\User  $user
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user)
    {
        $user = $this->model::findOrFail($user->id);

        $data = $request->input();
        $roles = [];
        if(isset($data['id'])) unset($data['id']);
        if(isset($data['_method'])) unset($data['_method']);
        if(isset($data['updated_at'])) unset($data['updated_at']);
        if(isset($data['roles'])) {
            $roles = array_column($data['roles'], 'id');
            unset($data['roles']);
        }

        if(isset($data['password'])) {
            $password = $data['password'];
            $data['password'] = bcrypt($password);
        }

        if(count($data)) $user->update($data);
        if(count($roles)) $user->roles()->sync($roles);

        return $this->show($user);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \Illuminate\Foundation\Auth\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        $user = $this->model::findOrFail($user->id);
        //$user->roles()->detach(); //???
        $user->delete(); // OR $user->forceDelete(); //???
        return $user;
    }
}
