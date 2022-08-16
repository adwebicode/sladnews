<?php

namespace App\Http\Controllers\User;

use App\Actions\Blog\BlogAction;
use App\Actions\Blog\BlogUserAction;
use App\Blog;
use App\BlogCategory;
use App\Helpers\FlashMsg;
use App\Helpers\LanguageHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\BlogInsertRequest;
use App\Http\Requests\BlogUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserPostController extends Controller
{
    private const BASE_PATH = 'frontend.user.dashboard.user-post.';

    public function __construct()
    {
        $this->middleware(['auth']);
    }

    public function get_user_id(){
        return Auth::guard('web')->user()->id;
    }

    public function user_index(Request $request){

        $all_user_posts = Blog::where('user_id',$this->get_user_id())->latest()->get();
        $default_lang = $request->lang ??  LanguageHelper::user_lang_slug();
        return view(self::BASE_PATH.'index',compact('all_user_posts','default_lang'));
    }

    public function user_new_blog(Request $request){
        $all_category = BlogCategory::select(['id','title'])->get();
        return view(self::BASE_PATH.'new')->with([
            'all_category' => $all_category,
            'default_lang' => $request->lang ?? LanguageHelper::default_slug(),
        ]);
    }

    public function user_store_new_blog(BlogInsertRequest  $request, BlogUserAction  $blogAction){
        $blogAction->store_execute($request);
        return back()->with(FlashMsg::item_new('Blog Post Created Successfully..'));
    }

    public function user_edit_blog(Request $request,$id){
        $blog_post = Blog::find($id);
        $all_category = BlogCategory::select(['id','title'])->get();
        return view(self::BASE_PATH.'edit')->with([
            'all_category' => $all_category,
            'blog_post' => $blog_post,
            'default_lang' => $request->lang ?? LanguageHelper::default_slug(),
        ]);
    }

    public function user_update_blog(BlogUpdateRequest $request, BlogUserAction $blogAction,$id) : RedirectResponse
    {
        $blogAction->update_execute($request,$id);
        return back()->with(FlashMsg::item_update('Post Updated Successfully..'));
    }

    public function user_delete_blog_all_lang(Request $request,BlogUserAction $action, $id){
        $action->delete_execute($request,$id,'delete');
        return redirect()->back()->with(FlashMsg::item_delete('Blog Post Deleted Successfully..'));
    }

    public function bulk_action_blog(Request $request){
        Blog::whereIn('id',$request->ids)->delete();
        return response()->json(['status' => 'ok']);
    }

    public function user_clone_blog(Request $request, BlogUserAction $blogAction)
    {
        $blogAction->clone_blog_execute($request);
        return back()->with(FlashMsg::item_clone('Blog Cloned..'));
    }

    //=============================== FORCE DELETE AND RESTORE FUNCTIONS =================================

    public function trashed_blogs(Request $request){
        $trashed_blogs = Blog::where('user_id',$this->get_user_id())->onlyTrashed()->get();
        $default_lang = $request->lang ?? LanguageHelper::default_slug();
        return view(self::BASE_PATH.'trashed',compact('trashed_blogs','default_lang'));
    }

    public function user_restore_trashed_blog($id){
        Blog::where('user_id',$this->get_user_id())->withTrashed()->find($id)->restore();
        return back()->with(FlashMsg::settings_update('Trashed Blog Restored Successfully..'));
    }

    public function user_delete_trashed_blog(Request $request, BlogUserAction $act, $id){

        $act->delete_execute($request,$id,'trashed_delete');
        return back()->with(FlashMsg::item_delete('Blog Post Deleted Forever'));
    }


}
