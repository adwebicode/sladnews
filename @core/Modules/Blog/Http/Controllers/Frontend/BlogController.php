<?php

namespace Modules\Blog\Http\Controllers\Frontend;

use App\Admin;
use App\Blog;
use App\BlogCategory;
use App\BlogComment;
use App\Helpers\FlashMsg;
use App\Helpers\LanguageHelper;
use App\Helpers\SanitizeInput;
use Illuminate\Routing\Controller;
use App\Mail\BasicMail;
use App\Tag;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Session;
use phpDocumentor\Reflection\Types\Null_;
use function PHPUnit\Framework\isNull;

class BlogController extends Controller
{

    private const BASE_PATH = 'blog::frontend.';

    public function blog_single($slug)
    {

        $blog_post = Blog::where(['slug'=> $slug,'status'=> 'publish'])->first();

        if (empty($blog_post)) {
            abort(404);
        }
        

        $blogCommentCount = BlogComment::where('blog_id', $blog_post->id)
            ->where('parent_id', null)
            ->count();

        $blogComments = BlogComment::with('reply')
            ->where('parent_id', null)
            ->where('blog_id', $blog_post->id)
            ->orderBy('id', 'desc')
            ->take(5)->get();

        $cat_id = '';
        foreach ($blog_post->category_id as $item) {
            $cat_id .= $item->id;
        }

        $all_related_blog = Blog::with(['user', 'admin'])->whereJsonContains('category_id', $cat_id)->where('status','publish')->orderBy('id', 'desc')->take(2)->get();

        if (is_null($blog_post->views)) {
            Blog::where('id', $blog_post->id)->update(['views' => 0]);
        } else {
            Blog::where('id', $blog_post->id)->increment('views');
        }

        $blog_details_variant = get_static_option('blog_details_variant');

        if(in_array($blog_details_variant,['01'])){
            $details_page = 'details-01';
        }else{
            $details_page = 'details-02';
        }
        
        $totalWords = str_word_count(strip_tags($blog_post->blog_content));
        $minutesToRead = round($totalWords / 200);
        $read_duration = (int) max(1, $minutesToRead);

        return view(self::BASE_PATH.'blog.blog-single-variant.'.$details_page.'')->with([
            'blog_post' => $blog_post,
            'all_related_blog' => $all_related_blog,
            'blogCommentCount' => $blogCommentCount,
            'blogComments' => $blogComments,
            'read_duration' => $read_duration
        ]);
    }

    public function category_wise_blog_page($id)
    {
        $all_blogs = Blog::whereJsonContains('category_id', $id)->orderBy('id', 'desc')->paginate(get_static_option('blog_category_item_show'));
        $category_name = BlogCategory::where(['id' => $id])->first()->title;

        return view(self::BASE_PATH.'blog.blog-category')->with([
            'all_blogs' => $all_blogs,
            'category_name' => $category_name,
        ]);
    }

    public function autocompleteSearch(Request $request)
    {
        $query = $request->get('query');
        $all_data = Blog::where('status', 'publish')
            ->whereJsonContains('tag_id', $query)
            ->orWhere('title', 'LIKE', "%$query%")->paginate(10);

        $html_markup = '';
        foreach ($all_data as $data) {
            $route = route('frontend.blog.single', ['slug' => $data->slug]);
            $image = render_image_markup_by_attachment_id($data->image,'','thumb',false);
            $html_markup .= '<li class="article-wrap"><a href="' . $route . '"> '.$image.' <i class="fas fa-newspaper"></i> ' . Str::words($data->title, 5) . '</a></li>';
        }
        return response()->json($html_markup);
    }

    public function tags_wise_blog_page($tag)
    {
        $all_blogs = Blog::whereJsonContains('tag_id', $tag)->paginate(get_static_option('blog_tags_item_show'));

        return view(self::BASE_PATH.'blog.blog-tags')->with([
            'all_blogs' => $all_blogs,
            'tag_name' => $tag,
        ]);
    }


    public function blog_search_page(Request $request)
    {
        $request->validate([
            'search' => 'required'
        ],
            ['search.required' => 'Enter anything to search']);

        $all_blogs = Blog::Where('title', 'LIKE', '%' . $request->search . '%')
            ->orderBy('id', 'desc')->paginate(get_static_option('blog_search_item_show'));

        return view(self::BASE_PATH.'blog.blog-search')->with([
            'all_blogs' => $all_blogs,
            'search_term' => $request->search,

        ]);
    }

    public function blog_get_search(Request $request)
    {
        $all_blogs = Blog::Where('title', 'LIKE', '%' . $request->term . '%')
            ->orderBy('id', 'desc')->paginate(6);

        return view(self::BASE_PATH.'blog.blog-search')->with([
            'all_blogs' => $all_blogs,
            'search_term' => $request->term,

        ]);
    }

    public function blog_comment_store(Request $request)
    {
        $request->validate([
            'comment_content' => 'required'
        ]);

        $content = BlogComment::create([
            'blog_id' => $request->blog_id,
            'user_id' => $request->user_id,
            'parent_id' => $request->comment_id,
            'commented_by' => $request->commented_by,
            'comment_content' => purify_html($request->comment_content),
        ]);

        try {
            Mail::to(get_static_option('site_global_email'))->send(new BasicMail([
                'subject' => __('You have a comment from') . ' ' . get_static_option('site_title'),
                'message' => __('you have a new comment submitted by') . ' ' . Auth::user()->name . ' ' . __('Email') . ' ' . Auth::user()->email . ' .' . __('check admin panel for more info'),
            ]));

        } catch (\Exception $e) {
            return redirect()->back()->with(FlashMsg::error($e->getMessage()));
        }


        return response()->json([
            'msg' => __('Your comment sent succefully'),
            'type' => 'success',
            'status' => 'ok',
            'content' => $content,
        ]);
    }

    public function load_more_comments(Request $request)
    {
        $all_comment = BlogComment::with(['blog', 'user','reply'])
            ->where('parent_id',null)
            ->orderBy('id','desc')
            ->skip($request->items)
            ->take(5)
            ->get();

        $markup = '';
        foreach ($all_comment as $item) {
            $parent_image = render_image_markup_by_attachment_id(optional($item->user)->image,'','',false);
            $avatar_image = render_image_markup_by_attachment_id(get_static_option('single_blog_page_comment_avatar_image'),'','',false);
            $commented_user_image = $parent_image ? $parent_image : $avatar_image;

            $var_data_parent_name = optional($item->user)->name;
            $title = optional($item->user)->name ?? '';
            $created_at = date('d F Y', strtotime($item->created_at ?? ''));
            $comment_content = $item->comment_content;
            $data_id = $item->id;
            $replay_mark = '';
            $replay_text = __('Reply');


$replay_mark .= <<<REPLA
    <div class="reply">
         <a href="#" data-comment_id="{$data_id}}" class="reply-btn btn-replay"><i class="las la-reply icon"></i><span class="text">{$replay_text}</span></a>
    </div>
REPLA;

            $repl = auth('web')->check() && auth('web')->id() != $item->user_id ? $replay_mark : '';

            $li_data = '';
            foreach ($item->reply as $repData) {
                $child_image = render_image_markup_by_attachment_id(optional($repData->user)->image ??  get_static_option('single_blog_page_comment_avatar_image'),'','',false);
                $child_user_name = optional($repData->user)->name ?? '';
                $child_commented_date = date('d F Y', strtotime($repData->created_at ?? ''));
                $child_comment = $repData->comment_content ?? '';


$li_data .= <<<LIDATA
    <li>
    <div class="single-comment-wrap">
        <div class="thumb">
          {$child_image}
        </div>
        <div class="content">
            <div class="content-top">
                <div class="left">
                    <h4 class="title">{$child_user_name}</h4>
                    <ul class="post-meta">
                        <li class="meta-item">
                            <i class="lar la-calendar icon"></i>
                            {$child_commented_date}
                        </li>
                    </ul>
                </div>
            </div>
            <p class="comment common-para">{$child_comment}</p>
        </div>
    </div>
</li>
LIDATA;
 }

    $markup .= <<<HTML

    <ul class="comment-list">
    <li>
        <div class="single-comment-wrap">
            <div class="thumb">
                {$commented_user_image}
            </div>
            <div class="content">
                <div class="content-top">
                    <div class="left">
                        <h4 class="title" data-parent_name="{$var_data_parent_name}">{$title}</h4>
                        <ul class="post-meta">
                            <li class="meta-item comment-date">
                                <i class="lar la-calendar icon"></i>
                               {$created_at}
                            </li>
                        </ul>
                    </div>
                </div>
                <p class="comment common-para">{$comment_content}
                </p>

                {$repl}
            </div>
        </div>
    </li>

    <li class="has-children">
        <ul>
            {$li_data}
        </ul>
    </li>

</ul>



HTML;
}

        return response()->json(['blogComments' => $all_comment, 'markup' => $markup]);
    }

    public function user_created_blogs($user, $id)
    {
        $id_type = $user === 'user' ? 'user_id' : 'admin_id';
        $all_blogs = Blog::where(['created_by' => $user, $id_type => $id, 'status' => 'publish'])->paginate(6);
        $user_info = $user === 'user' ? User::find($id) : Admin::find($id);
        if (empty($user_info)) {
            abort(404);
        }
        return view(self::BASE_PATH.'blog.user-blog', compact('all_blogs', 'user_info'));
    }

    public function author_profile($id)
    {
        $author_info = User::find($id);
        $all_blogs = Blog::where('user_id', $id)->where('status','publish')->paginate(6);
        if (empty($author_info)) {
            abort(404);
        }
        return view(self::BASE_PATH.'blog.author-profile', compact('all_blogs', 'author_info'));
    }


    public function get_tags_by_ajax(Request $request)
    {
        $query = $request->get('query');
        $filterResult = Tag::Where('name', 'LIKE', '%' . $query . '%')->get();
        $html_markup = '';
        $result = [];
        foreach ($filterResult as $data) {
            array_push($result, $data->name);
        }
        return response()->json(['result' => $result]);
    }


    public function get_blog_by_ajax(Request $request)
    {
        $current_lang = LanguageHelper::user_lang_slug();
        $blogs = Blog::where(['status' => 'publish'])->whereJsonContains('category_id', $request->id)->take(4)->get();

        $news_markup = '';
        $colors = ['bg-color-e','bg-color-a','bg-color-b','bg-color-g','bg-color-c'];
        foreach ($blogs as $key=> $item) {

            $image_markup = render_image_markup_by_attachment_id($item->image, '','',false);
            $route = route('frontend.blog.single', $item->slug);
            $title = Str::words(SanitizeInput::esc_html($item->getTranslation('title', $current_lang) ?? ''),9);
            $date = date('M d, Y', strtotime($item->created_at));
            $category_markup2 = '';

            foreach ($item->category_id as $catItem) {
                $category2 = $catItem->getTranslation('title', $current_lang);
                $category_route2 = route('frontend.blog.category', ['id' => $catItem->id, 'any' => Str::slug($catItem->title)]);
                $category_markup2 .= '<a class="category-style-01 v-02 '.$colors[$key % count($colors)].'"  href="' . $category_route2 . '">' . $category2 . '</a>';
            }


            if ($item->created_by === 'user') {
                $user_id = $item->user_id;
            } else {
                $user_id = $item->admin_id;
            }

            $created_by_url = !is_null($user_id) ? route('frontend.user.created.blog', ['user' => $item->created_by, 'id' => $user_id]) : route('frontend.blog.single', $item->slug);

            $comment_count = BlogComment::where('blog_id', $item->id)->count();
            $comment_condition_check = $comment_count == 0 ? 0 : $comment_count;

            $created_by = SanitizeInput::esc_html($item->author ?? __('Anonymous'));


            //author image
            $author = NULL;
            if (!isNull($item->user_id)) {
                $author = optional($item->user);
            } else if (!isNull($item->admin_id)) {
                $author = optional($item->admin);
            } else {
                $author = optional($item->admin);
            }
            $user_image = render_image_markup_by_attachment_id($author->image, 'image','',false);

            $avatar_image = render_image_markup_by_attachment_id(get_static_option('single_blog_page_comment_avatar_image'), 'image','',false);
            $created_by_image = $user_image ? $user_image : $avatar_image;


  $news_markup .= <<<HTML

 <div class="col-sm-6 col-md-6 col-lg-6">
    <div class="blog-grid-style-01 v-02">
        <div class="img-box">
        <a href="{$route}">{$image_markup} </a>
            <div class="tag-box left">
               {$category_markup2}
            </div>
        </div>
        <div class="content">
            <div class="post-meta color-black">
                <ul class="post-meta-list">
                    <li class="post-meta-item">
                        <a href="{$created_by_url}">
                          {$created_by_image}
                            <span class="text">{$created_by}</span>
                        </a>
                    </li>
                    <li class="post-meta-item date">
                        <span class="text">{$date}</span>
                    </li>
                    <li class="post-meta-item">
                        <a href="{$route}">
                            <span class="text">{$comment_condition_check} Comments</span>
                        </a>
                    </li>
                </ul>
            </div>
            <h4 class="title">
                <a href="{$route}">{$title}</a>
            </h4>
        </div>
    </div>
</div>

HTML;
}
        return response()->json(['markup' => $news_markup]);

    }
    
    public function user_blog_password(Request $request){

        $request->validate([
           'user_blog_password'=> 'required|string|max:20'
        ]);

        $blog_id = $request->password_form_id;
        $original_password = $request->original_password;
        $user_password = $request->user_blog_password;
         Session::put('user_given_password',$user_password);

        if($original_password  == $user_password){
            return redirect()->back();
        }else{
            return redirect()->back()->with(FlashMsg::item_delete('Password Not Matching..!'));
        }
    }


}
