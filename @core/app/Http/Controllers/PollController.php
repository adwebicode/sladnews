<?php

namespace App\Http\Controllers;

use App\Helpers\FlashMsg;
use App\Poll;
use App\PollInfo;
use Illuminate\Http\Request;

class PollController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:poll-list|blog-poll-create|blog-poll-edit|blog-poll-delete',['only' => ['index']]);
        $this->middleware('permission:poll-create',['only' => ['new_poll','store_poll']]);
        $this->middleware('permission:poll-edit',['only' => ['edit_poll','update_poll']]);
        $this->middleware('permission:poll-delete',['only' => ['bulk_action','delete_poll']]);
    }

    public function index()
    {
        $all_polls = Poll::latest()->get();
        return view('backend.pages.polls.index',compact('all_polls'));
    }

    public function new_poll()
    {
        return view('backend.pages.polls.new');
    }

    public function store_poll(Request $request)
    {
        $request->validate([
            'question'=>'required|string|max:191',
            'options'=> 'required',
            'status'=> 'required'
        ]);

       Poll::create([
           'question' => purify_html($request->question),
           'options' => purify_html(json_encode($request->options)),
           'status' => $request->status,
       ]);

       return redirect()->back()->with(FlashMsg::item_new('New Poll Created Successfully'));
    }

    public function edit_poll($id)
    {
        $poll = Poll::find($id);
        return view('backend.pages.polls.edit',compact('poll'));
    }

    public function update_poll(Request $request,$id)
    {
        $request->validate([
            'question'=>'required|string|max:191',
            'options'=> 'required',
            'status'=> 'required'
        ]);

        $options_insert = !empty($request->options) ? $request->options : [];

        Poll::where('id',$id)->update([
            'question' => purify_html($request->question),
            'options' => purify_html(json_encode($options_insert)),
            'status' => $request->status,
        ]);

        return redirect()->back()->with(FlashMsg::item_new(' Poll Updated Successfully'));
    }

    public function view_result($id){
        $poll = Poll::find($id);
        $vote_cart = [];
        $poll_info = PollInfo::where('poll_id', $poll->id)->get();

        foreach ($poll_info as $pl_info){
            $vote_cart[$pl_info->vote_name] = $poll_info->where('vote_name',$pl_info->vote_name)->count();
        }

        return view('backend.pages.polls.result',compact('poll','poll_info','vote_cart'));
    }

    public function delete_poll($id){
        Poll::find($id)->delete();
        return redirect()->back()->with(FlashMsg::item_new(' Poll Deleted Successfully'));
    }

    public function bulk_action(Request $request){
        Poll::whereIn('id',$request->ids)->delete();
        return response()->json(['status' => 'ok']);
    }
}
