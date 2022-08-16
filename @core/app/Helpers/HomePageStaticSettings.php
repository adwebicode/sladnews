<?php

namespace App\Helpers;

class HomePageStaticSettings
{
    public static function default_settings(){
        return [
            'site_title',
            'site_logo',
            'site_favicon',
        ];
    }
    public function home_01(){
        $user_lang = get_user_lang();
        $list = [


        ];
        return array_merge(self::default_settings(),$list);
    }
    public function home_02(){
        $user_lang_2 = get_user_lang();
        $list = [


        ];

        return array_merge(self::default_settings(),$list);
    }
    public function home_03(){
        $user_lang_3 = get_user_lang();
        $list = [


        ];
        return array_merge(self::default_settings(),$list);
    }
    public function home_04(){
        $user_lang_4 = get_user_lang();
        $list = [

        ];
        return array_merge(self::default_settings(),$list);
    }

    public static function get_home_field($homepage_id){
        $new_self = new self();
        $home_var = 'home_'.$homepage_id;
        return $new_self->$home_var();
    }
}




