<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;
use App\Role;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\API\CreateUserRequest;
use App\Http\Requests\API\UpdateUserRequest;

class UserController extends Controller
{

    /**
     * Display online user list
     *
     * @return \Illuminate\Http\Response
     */
    public function online_user()
    {
        return User::getOnlineUsers();
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

        $key = $request->search_key;
        $role_id = $request->role_id;
        $online = $request->online;

        $users = User::with('role');

        if(!empty($key)){
            $users->where('name','like',"%".$key."%")->orWhere('email','like',"%".$key."%");
        }
        if(!empty($role_id)){
            $users->where('role_id',$role_id);
        }
        if(!empty($online)){
            $users->where('online',$online);
        }


        return $users->latest()->paginate(5);

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreateUserRequest $request)
    {
        User::create([
            'name'=>$request->name,
            'email'=>$request->email, 
            'password'=>Hash::make($request->password),
            'role_id'=>$request->role_id,
        ]);

        return ['message'=>'ユーザー情報を登録しました。'];        
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateUserRequest $request, $id)
    {
        $user = User::findOrFail($id);
        $user->name = $request->name;
        $user->role_id = $request->role_id;
        $user->email = $request->email;
        $user->password = Hash::make($request->password);
        $user->save();


        return ['message'=>'ユーザー情報を更新しました。'];
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $user = User::findOrFail($id);

        $user->delete();

        return ['message'=>'指定したレコードは削除されました'];

    }
}
