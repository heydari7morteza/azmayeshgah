<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Ticket;
use App\Models\User;
use App\Models\Option;
use App\Models\Device;
use Illuminate\Support\Facades\Auth;

class TicketController extends Controller
{
    

    public function all_tickets(){
        $tickets = Ticket::get();
        $user = User::find(Auth::user()->id);
        
        $ticket_option = array();
        $ticket = Option::where('key' , 'ticket_status')->first();
        $ticket_option = json_decode($ticket['value'] , true);

        if($tickets->isNotEmpty()){
            return view ('tickets.all-tickets' , compact('tickets' , 'ticket_option'));
        }else{

        $user = User::find(Auth::user()->id);
        $isAdmin = $user->type;
        
        $device = $user->devices;

        $ticket_option = array();
        $ticket = Option::where('key' , 'ticket_status')->first();
        $ticket_option = json_decode($ticket['value'] , true); 
        
        return view('tickets.createTicket' , compact('device' , 'user' , 'ticket_option' , 'isAdmin'));
        }
    }

    public function showTicket($id){
        $ticket_option = array();
        $ticket = Option::where('key' , 'ticket_status')->first();
        $ticket_option = json_decode($ticket['value'] , true);

        $user = User::find($id);
        if($user->type == 0){
            $user = User::find($id);
            $tickets = $user->admin_tickets;
            $user_admin = 0; 
            return view ('tickets.ticket' , compact('tickets' , 'user_admin' , 'user' , 'ticket_option'));
        }elseif($user->type == 1){
            $user = User::find($id);
            $tickets = $user->tickets;
            $user_admin = 1;
            return view ('tickets.ticket' , compact('tickets' , 'user_admin' , 'user', 'ticket_option'));
        }
    }


    public function editTicket($id){
        $ticket = Ticket::find($id);
        $ticket_option = array();
        $ticket_status = Option::where('key' , 'ticket_status')->first();
        $ticket_option = json_decode($ticket_status['value'] , true);
        return view ('tickets.editTicket' , compact('ticket' , 'ticket_option'));
    }

    public function checkEditTicket(Request $request ,$id){
        $massage = [
            'name.required' => ' نام ضروری است',
        ];

        
        $validatedData = $request->validate([
            'name' => 'required',
        ] , $massage);

        $ticket = Ticket::find($id);
        $user = $ticket->user;
        $device = $ticket->device;

        $ticket->device()->associate($device);
        $ticket->device_id = $request->device_id;
        $ticket->user()->associate($user);
        $ticket->user_id = $request->user_id;
        $ticket->admin()->associate($user);
        $ticket->admin_id = $request->admin_id;


        $ticket->type = $request->type;
        $ticket->status = $request->status;
        $ticket->name = $request->name;

        if(isset($request->description)){
            $ticket->description = $request->description;
        }

        $ticket->save();
        $msg = 'تیکت با موفقیت ویرایش شد';
        if(Auth::user()->type == 0){
            return redirect(route('all_tickets'))->with('success' , $msg);
        }else{
            return redirect(route('showTicket' , Auth::user()->id ))->with('success' , $msg);
        }

        
        
    }


    public function createTicket($id){
        $user = User::find($id);
        $isAdmin = $user->type;
        
        $device = $user->devices;

        $ticket_option = array();
        $ticket = Option::where('key' , 'ticket_status')->first();
        $ticket_option = json_decode($ticket['value'] , true); 

        return view('tickets.createTicket' , compact('device' , 'user' , 'ticket_option' , 'isAdmin'));
        
    }

    public function checkCreateTicket(Request $request , $id){

        $massage = [
            'name.required' => ' نام ضروری است',
        ];
        
        $validatedData = $request->validate([
            'name' => 'required',
        ] , $massage);

        
        $ticket = new Ticket();
        $device = Device::find($request->device_id);
        $ticket->device()->associate($device);

        $user = User::find($request->user_id);
        $ticket->user()->associate($user);
        $ticket->device_id = $request->device_id;
        $ticket->user_id = $request->user_id;
        $ticket->admin()->associate($user);
        $ticket->admin_id = $request->admin_id;


        $ticket->type = $request->type;
        $ticket->status = $request->status;
        $ticket->name = $request->name;

        if(isset($request->description)){
            $ticket->description = $request->description;
        }

        $ticket->save();
        $msg = 'تیکت با موفقیت ایجاد شد';
        if($user->type == 0){
            return redirect(route('all_tickets'))->with('success' , $msg);
        }else{
            return redirect(route('showTicket', $id) )->with('success' , $msg);
        }
        
    }


    public function deleteTicket($id){
        $ticket = Ticket::find($id);
        $ticket->delete();
        $msg = 'تیکت با موفقیت حذف شد';
        $user = User::find(Auth::user()->id);
        if($user->type == 0){
            return redirect(route('all_tickets'))->with('success' , $msg);
        }else{
            return redirect(route('showTicket', Auth::user()->id) )->with('success' , $msg);
        }
    }

}
