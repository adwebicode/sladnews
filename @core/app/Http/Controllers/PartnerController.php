<?php

namespace App\Http\Controllers;

use App\Helpers\FlashMsg;
use App\Partner;
use Illuminate\Http\Request;

class PartnerController extends Controller
{
    public function __construct(){
        $this->middleware('auth:admin');
        $this->middleware('permission:partner-list|partner-create|partner-edit|partner-delete',['only'=> 'index']);
        $this->middleware('permission:partner-create',['only'=> ['store']]);
        $this->middleware('permission:partner-edit',['only'=> ['update']]);
        $this->middleware('permission:partner-delete',['only'=> ['delete','bulk_action']]);
    }

    public function index()
    {
        $all_partners = Partner::latest()->get();
        return view('backend.pages.partners',compact('all_partners'));
    }

    public function store(Request $request){
        $this->validate($request,[
            'url' => 'required|string',
            'image' => 'nullable|string',
        ]);

        $data = [
            'url' => purify_html($request->url),
            'image' => $request->image,
        ];

        Partner::create($data);

        return redirect()->back()->with(FlashMsg::item_new('Client Added Succesfully'));
    }

    public function update(Request $request){
        $this->validate($request,[
            'url' => 'required|string',
            'image' => 'nullable|string',
        ]);

        Partner::findOrFail($request->id)->update([
            'url' => purify_html($request->url),
            'image' => $request->image,
        ]);

        return redirect()->back()->with([
            'msg' => __('Client Update Success...'),
            'type' => 'success'
        ]);
    }

    public function delete(Request $request,$id){
        Partner::findOrFail($id)->delete();
        return redirect()->back()->with([
            'msg' => __('Client Delete Success...'),
            'type' => 'danger'
        ]);
    }

    public function bulk_action(Request $request){

        Partner::whereIn('id',$request->ids)->delete();
        return redirect()->back()->with([
            'msg' => __('Client Delete Success...'),
            'type' => 'danger'
        ]);
    }
}
