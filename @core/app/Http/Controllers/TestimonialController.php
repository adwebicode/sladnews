<?php

namespace App\Http\Controllers;

use App\Helpers\FlashMsg;
use App\Helpers\LanguageHelper;
use App\Testimonial;
use Illuminate\Http\Request;

class TestimonialController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
        $this->middleware('permission:testimonial-list|testimonial-create|testimonial-edit|testimonial-delete',['only' => ['index']]);
        $this->middleware('permission:testimonial-create',['only' => ['store']]);
        $this->middleware('permission:testimonial-edit',['only' => ['update','clone']]);
        $this->middleware('permission:testimonial-delete',['only' => ['delete','bulk_action']]);
    }
    public function index(Request $request){
        $all_testimonials = Testimonial::all();
        return view('backend.pages.testimonial')->with([
            'all_testimonials' => $all_testimonials,
            'default_lang'=> $request->lang ?? LanguageHelper::default_slug()
        ]);
    }
    public function store(Request $request){

        $this->validate($request,[
            'name' => 'required|string|max:191',
            'description' => 'required',
            'designation' => 'string|max:191',
            'status' => 'string|max:191',
            'image' => 'nullable|string|max:191',
        ]);

        $testimonial = new Testimonial();
        $testimonial->setTranslation('name',$request->lang, purify_html($request->name))
        ->setTranslation('description',$request->lang, purify_html($request->description))
        ->setTranslation('designation',$request->lang, purify_html($request->designation));
        $testimonial->image = $request->image;
        $testimonial->status = $request->status;
        $testimonial->save();


        return redirect()->back()->with(FlashMsg::item_new('New Testimonial Added'));
    }

    public function update(Request $request){
        $this->validate($request,[
            'name' => 'required|string|max:191',
            'description' => 'required',
            'designation' => 'string|max:191',
            'status' => 'string|max:191',
            'image' => 'nullable|string|max:191',
        ]);

        $testimonial = Testimonial::find($request->id);
        $testimonial->setTranslation('name',$request->lang, purify_html($request->name))
                    ->setTranslation('description',$request->lang, $request->description)
                    ->setTranslation('designation',$request->lang, $request->designation);
                $testimonial->image = $request->image;
                $testimonial->status = $request->status;
                $testimonial->save();

        return redirect()->back()->with(FlashMsg::item_update('Testimonial Updated'));
    }

    public function clone(Request $request){
        $testimonial = Testimonial::find($request->item_id);

        Testimonial::create([
            'name' => $testimonial->name,
            'description' => $testimonial->description,
            'status' => 'draft',
            'designation' => $testimonial->designation,
            'image' => $testimonial->image
        ]);

        return redirect()->back()->with(FlashMsg::item_clone('Testimonial Clone Successfully'));
    }

    public function delete(Request $request,$id){

        $testimonial = Testimonial::find($id)->delete();
        return redirect()->back()->with(FlashMsg::item_delete('Testimonial Deleted'));
    }

    public function bulk_action(Request $request){
        $all = Testimonial::find($request->ids);
        foreach($all as $item){
            $item->delete();
        }
        return response()->json(['status' => 'ok']);
    }
}
