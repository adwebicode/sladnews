<?php

namespace App\Http\Controllers;
use App\Faq;
use App\Helpers\LanguageHelper;
use Illuminate\Http\Request;

class FaqController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:faq-list|faq-create|faq-edit|faq-delete',['only' => ['index']]);
        $this->middleware('permission:faq-create',['only' => ['store']]);
        $this->middleware('permission:faq-edit',['only' => ['update','clone']]);
        $this->middleware('permission:faq-delete',['only' => ['delete','bulk_action']]);
    }
    public function index(Request $request){
        $all_faqs = Faq::latest()->get();
        return view('backend.pages.faqs')->with(['all_faqs'=> $all_faqs, 'default_lang'=> $request->lang ?? LanguageHelper::default_slug()]);
    }
    public function store(Request $request){
        $this->validate($request,[
            'title' => 'required|string|max:191',
            'description' => 'required|string',
            'status' => 'nullable|string|max:191',
        ]);

        $faq = new Faq();
        $faq->setTranslation('title',$request->lang, purify_html($request->title));
        $faq->setTranslation('description',$request->lang, purify_html_raw($request->description));
        $faq->status =  $request->status;
        $faq->is_open =  !empty($request->is_open) ? 'on' : '';
        $faq->save();

        return redirect()->back()->with(['msg' => __('New Faq Added...'),'type' => 'success']);
    }

    public function update(Request $request){
        $this->validate($request,[
            'title' => 'required|string',
            'description' => 'required|string',
            'status' => 'nullable|string|max:191',
        ]);

        $faq =  Faq::findOrFail($request->id);
        $faq->setTranslation('title',$request->lang, purify_html($request->title));
        $faq->setTranslation('description',$request->lang, purify_html_raw($request->description));
        $faq->status =  $request->status;
        $faq->is_open = !empty($request->is_open) ? 'on' : '';
        $faq->save();

        return redirect()->back()->with(['msg' => __('Faq Updated...'),'type' => 'success']);
    }

    public function delete($id){
        Faq::find($id)->delete();
        return redirect()->back()->with(['msg' => __('Delete Success...'),'type' => 'danger']);
    }

    public function clone(Request $request){
        $faq_item = Faq::find($request->item_id);
        Faq::create([
            'title' => $faq_item->title,
            'description' => $faq_item->description,
            'status' => 'draft',
            'is_open' => !empty($faq_item->is_open) ? 'on' : '',
        ]);
        return redirect()->back()->with(['msg' => __('Clone Success...'),'type' => 'success']);
    }

    public function bulk_action(Request $request){
        $all = Faq::find($request->ids);
        foreach($all as $item){
            $item->delete();
        }
        return response()->json(['status' => 'ok']);
    }

}
