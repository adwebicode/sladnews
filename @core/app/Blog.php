<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;
use Spatie\Feed\Feedable;
use Spatie\Feed\FeedItem;
use Spatie\Translatable\HasTranslations;
use App\Helpers\LanguageHelper;

class Blog extends Model implements Feedable
{
    use HasFactory , HasTranslations, SoftDeletes;

    protected $table = 'blogs';
    protected $fillable = ['category_id',
        'user_id','title','slug','blog_content',
        'image','author','excerpt','status',
        'image_gallery','views','video_url','order_by',
        'visibility','featured','schedule_date',
        'admin_id','created_by','tag_id','breaking_news','password','password_verified'
    ];

    public $translatable  = ['title','blog_content','excerpt'];
    protected $dates = ['deleted_at'];



    public function toFeedItem(): FeedItem
    {

        return FeedItem::create([
            'id' => $this->id,
            'title' => $this->getTranslation('title',LanguageHelper::default_slug()),
            'summary' => $this->excerpt,
            'updated' => $this->updated_at,
            'link' => route('frontend.blog.single',$this->slug),
            'author' => $this->author ?? $this->created_by,
        ]);
    }

    public static function getFeedItems()
    {
        return Blog::where('status','publish')->orderBy('id','desc')->take(20)->get();
    }


    public function user(){
        return $this->belongsTo(User::class,'user_id');
    }

    public function admin(){
        return $this->belongsTo(Admin::class,'admin_id');
    }

    public function getCategoryIdAttribute($item){
        return !empty($item) ? BlogCategory::whereIn('id',json_decode($item))->get() : (object) [];
    }

    public function meta_data(){
        return $this->morphOne(MetaData::class,'meta_taggable');
    }

    public function author_data(){
        if ($this->attributes['created_by'] === 'user'){
            return User::find($this->attributes['user_id']);
        }
        return Admin::find($this->attributes['admin_id']);
    }

    public function comments(){
        return $this->hasMany(BlogComment::class,'blog_id','id');
    }

}

