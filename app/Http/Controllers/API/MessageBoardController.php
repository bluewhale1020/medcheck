<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\MessageBoard;
use App\Role;
use Auth;

use App\Events\MessageUpdated;

use Carbon\Carbon;

class MessageBoardController extends Controller
{

    public function index(){

        $user = auth('api')->user();

        $dt = Carbon::today()->subDay(2);
        $result = MessageBoard::with('user')->whereDate('created_at','>=',$dt->format('Y-m-d'))->latest()->limit(20)->get();        

        foreach ($result as $key => $record) {
            if($record->user_id == $user->id){
                $result[$key]->own = true;
            }else{
                $result[$key]->own = false;

            }
            $result[$key]->image_path = Role::getImagePath($record->user->role_id);
        }
        return $result;
    }

    public function store(Request $request){

        $user_id = auth('api')->user()->id;

        MessageBoard::create([
            'user_id'=>$user_id,
            'title'=>$request->title, 
            'message'=>$request->message,
        ]);

        broadcast(new MessageUpdated())->toOthers();

        return ['message'=>'コメントを登録しました。'];     
    }

    public function destroy($id)
    {
        $user = MessageBoard::findOrFail($id);

        $user->delete();

        broadcast(new MessageUpdated())->toOthers();

        return ['message'=>'指定したコメントは削除されました'];

    }    
}
