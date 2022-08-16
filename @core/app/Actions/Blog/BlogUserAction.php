<?php

namespace App\Actions\Blog;
use App\Blog;
use App\Helpers\LanguageHelper;
use App\MetaData;
use App\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class BlogUserAction
{

    public function store_execute(Request $request) :void {

        $blog = new Blog();
        $blog
            ->setTranslation('title',$request->lang, purify_html($request->title))
            ->setTranslation('blog_content',$request->lang, $request->blog_content)
            ->setTranslation('excerpt',$request->lang, purify_html($request->excerpt))
            ->save();

        $auth_id = Auth::guard('web')->user()->id;
        $user = User::where('id',$auth_id)->first();

        if($user->auto_post_approval == 1){
            $blog->status = 'publish';
        }else{
            $blog->status = 'pending';
        }

        $slug = !empty($request->slug) ? $request->slug : Str::slug($request->title);
        $slug_check = Blog::where(['slug' => $slug])->count();
        $slug = $slug_check > 0 ? $slug.'-2' : $slug;

        $blog->slug = purify_html($slug);

        $blog->category_id = json_encode($request->category_id);

        $tag = $request->tag_id;
        $new_tag_data = explode(',',$tag[0]);

        $blog->tag_id = json_encode($new_tag_data) ?? [];
        $blog->admin_id = null;
        $blog->user_id = auth()->guard('web')->user()->id;
        $blog->author = auth()->guard('web')->user()->name;
        $blog->image = $request->image;
        $blog->image_gallery = $request->image_gallery;
        $blog->visibility = 'public';
        $blog->schedule_date = $request->schedule_date;
        $blog->views = 0;
        $blog->video_url = purify_html($request->video_url);
        $blog->video_duration = purify_html($request->video_duration);
        $blog->created_by = 'user';

        $Metas = [
            'meta_title'=> purify_html($request->meta_title),
            'meta_tags'=> purify_html($request->meta_tags),
            'meta_description'=> purify_html($request->meta_description),

            'facebook_meta_tags'=> purify_html($request->facebook_meta_tags),
            'facebook_meta_description'=> purify_html($request->facebook_meta_description),
            'facebook_meta_image'=> $request->facebook_meta_image,

            'twitter_meta_tags'=> purify_html($request->twitter_meta_tags),
            'twitter_meta_description'=> purify_html($request->twitter_meta_description),
            'twitter_meta_image'=> $request->twitter_meta_image,
        ];

        DB::beginTransaction();

        try {
            $blog->save();
            $blog->meta_data()->create($Metas);
            DB::commit();

        }catch (\Throwable $th){
            DB::rollBack();
        }

    }


    public function update_execute(Request $request ,$id) : void
    {
        $blog_update =  Blog::findOrFail($id);

        $blog_update
            ->setTranslation('title',$request->lang, purify_html($request->title))
            ->setTranslation('blog_content',$request->lang, $request->blog_content)
            ->setTranslation('excerpt',$request->lang, purify_html($request->excerpt))
            ->save();

        $slug = !empty($request->slug) ? $request->slug : Str::slug($request->title);
        $slug_check = Blog::where(['slug' => $slug])->count();
        $slug = $slug_check > 1 ? $slug.'-4' : $slug;

        if($request->lang === LanguageHelper::default_slug()){
            $blog_update->slug = purify_html($slug);
        }

        $blog_update->category_id = json_encode($request->category_id);

        $tag = $request->tag_id;
        $new_tag_data = explode(',',$tag[0]);

        $blog_update->tag_id = json_encode($new_tag_data) ?? [];
        $blog_update->admin_id = null;
        $blog_update->image = $request->image;
        $blog_update->image_gallery = $request->image_gallery;
        $blog_update->schedule_date = $request->schedule_date;
        $blog_update->views = 0;
        $blog_update->video_url = purify_html($request->video_url);
        $blog_update->video_duration = purify_html($request->video_duration);
        $blog_update->created_by = 'user';

        $Metas = [
            'meta_title'=> purify_html($request->meta_title),
            'meta_tags'=> $request->meta_tags,
            'meta_description'=> purify_html($request->meta_description),

            'facebook_meta_tags'=> purify_html($request->facebook_meta_tags),
            'facebook_meta_description'=> purify_html($request->facebook_meta_description),
            'facebook_meta_image'=> $request->facebook_meta_image,

            'twitter_meta_tags'=> purify_html($request->twitter_meta_tags),
            'twitter_meta_description'=> purify_html($request->twitter_meta_description),
            'twitter_meta_image'=> $request->twitter_meta_image,
        ];

        DB::beginTransaction();

        try {
            $blog_update->meta_data()->update($Metas);
            $blog_update->save();
            DB::commit();

        }catch (\Throwable $th){
            DB::rollBack();
        }
    }

    public function clone_blog_execute(Request $request)
    {

        $blog_details = Blog::findOrFail($request->item_id);
        $cloned_data = Blog::create([
            'category_id' =>  json_encode(optional($blog_details->category_id)->pluck('id')->toArray()),
            'tag_id' =>  json_encode($blog_details->tag_id),
            'slug' => !empty($blog_details->slug) ? $blog_details->slug : Str::slug($blog_details->title),
            'blog_content' => $blog_details->blog_content,
            'title' => $blog_details->title,
            'status' => 'draft',
            'excerpt' => $blog_details->excerpt,
            'image' => $blog_details->image,
            'image_gallery' => $blog_details->image,
            'views' => 0,
            'user_id' =>  Auth::guard('web')->user()->id,
            'admin_id' =>null,
            'author' => Auth::guard('web')->user()->name,
            'schedule_date' => $blog_details->schedule_date,
            'featured' => $blog_details->featured,
            'video_url' => $blog_details->video_url,
            'video_duration' => $blog_details->video_duration,
            'created_by' => $blog_details->created_by,
        ]);


        $meta_object = optional($blog_details->meta_data);
        $Metas = [
            'meta_title'=> $meta_object->meta_title,
            'meta_tags'=> $meta_object->meta_tags,
            'meta_description'=> $meta_object->meta_description,

            'facebook_meta_tags'=> $meta_object->facebook_meta_tags,
            'facebook_meta_description'=> $meta_object->facebook_meta_description,
            'facebook_meta_image'=> $meta_object->facebook_meta_image,

            'twitter_meta_tags'=> $meta_object->twitter_meta_tags,
            'twitter_meta_description'=> $meta_object->twitter_meta_description,
            'twitter_meta_image'=> $meta_object->twitter_meta_image,
        ];

        $cloned_data->meta_data()->save(MetaData::create($Metas));
    }

    public function delete_execute(Request $request, $id, $type = 'delete')
    {
        $auth_id = Auth::guard('web')->user()->id;
        switch ($type){
            case ('trashed_delete'):
                $blog = Blog::where('user_id',$auth_id)->withTrashed()->find($id);
                $blog->forceDelete();
                $blog->meta_data()->delete();
                break;
            default:
                $blog = Blog::where('user_id',$auth_id)->find($id);
                $blog->delete();
                break;
        }

    }
}