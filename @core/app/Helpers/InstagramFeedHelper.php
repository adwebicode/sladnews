<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Http;

class InstagramFeedHelper
{
    private $access_token;
    protected $base_url = 'https://graph.instagram.com/me/media?';
    private $fields = ['caption','id','media_type','media_url','permalink','thumbnail_url','timestamp','username'];
    public function __construct()
    {
        $this->access_token = get_static_option('instagram_access_token');
    }

    public function get_base_url(){
        return $this->base_url;
    }

    public function get_fields(){
        return implode(',',$this->fields);
    }

    public function get_access_token(){
        return $this->access_token;
    }

    public function fetch($limit = null){
        $data = [
            'fields' => $this->get_fields(),
            'access_token' => $this->get_access_token(),

        ];
        if (!is_null($limit)){
            $data['limit'] = $limit;
        }

        $response = Http::get($this->get_base_url(),$data);
        if ($response->ok()){
            return $response->object();
        }
        return false;
    }


}