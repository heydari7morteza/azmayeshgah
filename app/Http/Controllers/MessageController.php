<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Ticket;
use App\Models\Message;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class MessageController extends Controller
{
    

    public function showMessage($id){
        $ticket = Ticket::find($id);
        $messages = $ticket->messages;
        $user = User::find(Auth::user()->id);
        $isAdmin = $user->type;
        if($messages->isNotEmpty()){
            return view ('messages.message' , compact('ticket' , 'isAdmin' , 'user' , 'messages'));
        }else{
            return view ('messages.createMessage' , compact('ticket', 'user'));
        }

    }

    public function createMessage(Request $request ,$id){
        
        $message = [
            'text.required' =>'پیام را وارد کنید'
        ];

        $validatedData = $request->validate([
            'text'=>'required'
        ]);

        $message = new Message();
        $message->user_id = Auth::user()->id;
        $message->ticket_id = $request->ticket_id;
        $message->text = $request->text;

        
        $ticket = Ticket::find($id);
        $user = User::find(Auth::user()->id);
        if( $ticket->status == 0 && $user->type == 0){
            if($ticket->admin_id == null){
                $ticket->admin_id = Auth::user()->id;
            }
            $ticket->status = 1;
            $ticket->save();
        }
        
        $message->save();
        
        return redirect(route('showMessage',$id ));
    }
}