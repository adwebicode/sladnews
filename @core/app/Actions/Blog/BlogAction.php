<?php

namespace App\Actions\Blog;
use App\Blog;
use App\Helpers\LanguageHelper;
use App\MetaData;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class BlogAction
{

    public function store_execute(Request $request) :void {

        $blog = new Blog();
        $blog
            ->setTranslation('title',$request->lang, purify_html($request->title))
            ->setTranslation('blog_content',$request->lang,$request->blog_content)
            ->setTranslation('excerpt',$request->lang, purify_html($request->excerpt))
            ->save();

        $slug = !empty($request->slug) ? $request->slug : Str::slug($request->title);
        $slug_check = Blog::where(['slug' => $slug])->count();
        $slug = $slug_check > 0 ? $slug.'-2' : $slug;

         $blog->slug = purify_html($slug);
        $blog->category_id = json_encode($request->category_id);

        $tag = $request->tag_id;
        $new_tag_data = explode(',',$tag[0]);
        $blog->tag_id = json_encode($new_tag_data) ?? [];

        $blog->featured = $request->featured;
        $blog->password = $request->password;
        $blog->breaking_news = $request->breaking_news;
        $blog->order_by = $request->order_by;
        $blog->visibility = $request->visibility;
        $blog->status = $request->status;
        $blog->admin_id = Auth::guard('admin')->user()->id;
        $blog->user_id = null;
        $blog->author =Auth::guard('admin')->user()->name;
        $blog->image = $request->image;
        $blog->image_gallery = $request->image_gallery;
        $blog->schedule_date = $request->schedule_date;
        $blog->views = 0;
        $blog->video_url = purify_html($request->video_url);
        $blog->video_duration = purify_html($request->video_duration);
        $blog->created_by = 'admin';

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
        $blog->save();
        $blog->meta_data()->create($Metas);


    }


    public function update_execute(Request $request ,$id) : void
    {
        $blog_update =  Blog::findOrFail($id);

        $blog_update
            ->setTranslation('title',$request->lang, purify_html($request->title))
            ->setTranslation('blog_content',$request->lang,$request->blog_content)
            ->setTranslation('excerpt',$request->lang, purify_html($request->excerpt))
            ->save();

        $slug = !empty($request->slug) ? $request->slug : Str::slug($request->title);
        $slug_check = Blog::where(['slug' => $slug])->count();
        $slug = $slug_check > 1 ? $slug.'-3' : $slug;
        if($request->lang === LanguageHelper::default_slug()){
            $blog_update->slug = purify_html($slug);
        }
        $blog_update->category_id = json_encode($request->category_id);

        $tag = $request->tag_id;
        $new_tag_data = explode(',',$tag[0]);

        $blog_update->tag_id =  json_encode($new_tag_data) ?? [];
        $blog_update->featured = $request->featured;
        $blog_update->password = $request->password;
        $blog_update->breaking_news = $request->breaking_news;
        $blog_update->order_by = $request->order_by;
        $blog_update->visibility = $request->visibility;
        $blog_update->status = $request->status;
        $blog_update->image = $request->image;
        $blog_update->image_gallery = $request->image_gallery;
        $blog_update->schedule_date = $request->schedule_date;
        $blog_update->views = 0;
        $blog_update->video_url =$request->video_url;
        $blog_update->video_duration =$request->video_duration;

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
            'category_id' =>  json_encode(optional($blog_details->category_id)->pluck('id')->toArray()) ,
            'tag_id' =>  json_encode($blog_details->tag_id),
            'slug' => !empty($blog_details->slug) ? $blog_details->slug : Str::slug($blog_details->title),
            'blog_content' => $blog_details->blog_content,
            'title' => $blog_details->title,
            'status' => 'draft',
            'excerpt' => $blog_details->excerpt,
            'image' => $blog_details->image,
            'image_gallery' => $blog_details->image,
            'views' => 0,
            'user_id' => null,
            'admin_id' => Auth::guard('admin')->user()->id,
            'author' => Auth::guard('admin')->user()->name,
            'schedule_date' => $blog_details->schedule_date,
            'featured' => $blog_details->featured,
            'breaking_news' => $blog_details->breaking_news,
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
        switch ($type){
            case ('trashed_delete'):
                $blog = Blog::withTrashed()->find($id);
                $blog->forceDelete();
                $blog->meta_data()->delete();
                break;
            default:
                $blog = Blog::find($id);
                $blog->delete();
                break;
        }

    }
}