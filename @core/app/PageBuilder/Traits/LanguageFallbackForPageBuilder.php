<?php

namespace App\PageBuilder\Traits;

trait LanguageFallbackForPageBuilder
{
    public function setting_item($item){
        $settings = $this->get_settings($this->generateCacheKeyForSettings());
        return $settings[$item] ?? null;
    }
    
    public function generateCacheKey(){
        $settings = $this->get_settings($this->generateCacheKeyForSettings());
         return \Str::slug($this->addon_title()).($this->args['id'] ?? '').($this->args['page_id'] ?? '') ;
    }
    public function generateCacheKeyForSettings(){
        return 'addon-settings'.($this->args['id'] ?? '').($this->args['page_id'] ?? '') ;
    }
}