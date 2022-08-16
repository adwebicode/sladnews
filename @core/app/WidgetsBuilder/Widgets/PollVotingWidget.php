<?php
namespace App\WidgetsBuilder\Widgets;

use App\BlogCategory;
use App\EventCategory;
use App\Facades\GlobalLanguage;
use App\Helpers\FlashMsg;
use App\Helpers\LanguageHelper;
use App\Language;
use App\PageBuilder\Fields\NiceSelect;
use App\PageBuilder\Fields\Select;
use App\PageBuilder\Traits\LanguageFallbackForPageBuilder;
use App\Poll;
use App\PollInfo;
use App\WidgetsBuilder\WidgetBase;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class PollVotingWidget extends WidgetBase
{

    public function admin_render()
    {

        // TODO: Implement admin_render() method.
        $output = $this->admin_form_before();
        $output .= $this->admin_form_start();
        $output .= $this->default_fields();
        $widget_saved_values = $this->get_settings();

        //render language tab
        $output .= $this->admin_language_tab();
        $output .= $this->admin_language_tab_start();

        $all_languages = Language::all();
        foreach ($all_languages as $key => $lang) {
            $output .= $this->admin_language_tab_content_start([
                'class' => $key == 0 ? 'tab-pane fade show active' : 'tab-pane fade',
                'id' => "nav-home-" . $lang->slug
            ]);
            $widget_title = $widget_saved_values['widget_title_' . $lang->slug] ?? '';
            $output.= "<label>".__('Select Title')."</label>";
            $output .= '<div class="form-group"><input type="text" name="widget_title_' . $lang->slug . '" class="form-control" placeholder="' . __('Widget Title') . '" value="' . $widget_title . '"></div>';

            $output .= $this->admin_language_tab_content_end();
        }
        $output .= $this->admin_language_tab_end();
        //end multi langual tab option

        $all_polls = Poll::where(['status' => '1'])->get()->pluck('question', 'id')->toArray();
        $output .= Select::get([
            'name' => 'question',
            'multiple' => false,
            'label' => __('Polls'),
            'placeholder' => __('Select Poll'),
            'options' => $all_polls,
            'value' => $widget_saved_values['question'] ?? null,
        ]);

        $output .= Select::get([
            'name' => 'header_style',
            'label' => __('Header Style'),
            'options' => [
                '1' => __('Style One'),
                '2' => __('Style Two'),
                '4' => __('Style Three'),
            ],
            'value' => $widget_saved_values['header_style'] ?? null,
            'info' => __('You can change header style from here')
        ]);

        $output .= $this->admin_form_submit_button();
        $output .= $this->admin_form_end();
        $output .= $this->admin_form_after();

        return $output;
    }

    public function frontend_render()
    {
        // TODO: Implement frontend_render() method.
        $user_selected_language = GlobalLanguage::user_lang_slug();
        $widget_saved_values = $this->get_settings();

        $widget_title = purify_html($widget_saved_values['widget_title_' . $user_selected_language] ?? '');
        $header_style = $widget_saved_values['header_style'] ?? '';
        $polls = Poll::where(['status'=> 1, 'id'=> $widget_saved_values['question'] ])->orderBy('id', 'DESC')->first();
        if (is_null($polls)){
            return '<div class="alert alert-warning">'.__('This question is not available..!').'</div>';
        }
        $vote_cart = [];
        $pool_info = PollInfo::where('poll_id', $polls->id)->get();

        foreach ($pool_info as $pl_info){
            $vote_cart[$pl_info->vote_name] = $pool_info->where('vote_name',$pl_info->vote_name)->count();
        }
        $output = $this->widget_before('widget_archive'); //render widget before content

        $output.= '<div class=" widget widget-poll voting-custom wow bounceInUp" data-wow-duration="1.5s">';
        if (!empty($widget_title)) {
            $output .= '<h4 class="widget-title style-0'.$header_style.'">' . $widget_title . '</h4>';
        }
        $output .= '<ul class="list">';

            $poll_id = $polls->id;
            $output.='<h5 id="qs"> '.$polls->question.' </h5>';
            $output.='<h6 id="total_vote" class="text-center text-primary" style="display: none"> '.__("Total Vote : ").' '.$pool_info->count().' </h6>';
            $options = json_decode($polls->options, true);


            foreach ($options as $name) {
                $output .= '<li class="vote_item">
                            <label class="ml-1">
                          <input class="poll_radio" type="radio" name="vote" value="' . $name . '">       
                                ' . $name . '
                            </label>
                        </li>';
                  }
            $a = 0;

             foreach ($vote_cart as $name => $count) {
                $avg = $count / $pool_info->count() * 100 ;
                 $colors2 = ['#FEA47F','#BDC581','#EAB543','#55E6C1','#B33771'];
                 $output.= ' <div class="vote_progress_content" style="display: none">
                    
                      <div class="progress mt-4">
                          <div class="progress-bar text-left" role="progressbar" style="width: '. $avg .'% ; background-color: '.$colors2[$a % count($colors2)].' " aria-valuenow="'.$avg.'"
                                  aria-valuemin="0" aria-valuemax="100"><strong>'.$name . '  '. ("($count)").' <span class="progress-percentage">'.ceil($avg ).'%</span> </strong></div>
                            </div>
                     </div>';
                 $a++;
           }

        $output .= '</ul>';

        $auth_check = Auth::guard('web')->check();
        $auth_user_name = Auth::guard('web')->user()->name ?? '';
        $auth_user_email = Auth::guard('web')->user()->email ?? '';

        $name =  $auth_check ? $auth_user_name : '';
        $email =  $auth_check ? $auth_user_email : '';

        $output.= ' <div class="vote-login-details" style="display: none">
                        <form action="" id="poll_voting_form" method="post">
                          
                          <div class="error-wrap"></div>
                          <input type="hidden" name="id" id="id" value="'.$poll_id.'">
                          <input type="hidden" name="vote_name" id="vote_name" value="">
                            <div class="form-group">
                                  <input type="text" class="form-control" name="name" value="'.$name.'" placeholder="Enter your name" id="voter_name">
                            </div>
                            
                            <div class="form-group">
                                 <input type="email"  class="form-control" name="email" value="'.$email.'" placeholder="Enter your email" id="voter_email">
                            </div>
                         
                        </form>
                   </div>';


           $output.= ' <div class="vote-button-content">
                        <button id="vote_btn" class="btn btn-info btn-sm" style="display: none"> '.__('Vote').'</button>
                        <button type="submit" id="submit_vote_btn" class="btn btn-info btn-sm" style="display: none"> '.__('Submit Vote').'</button>
                        <a href="" class="view_results_btn">'.__('View Results').'</a>
                        <a href="" class="view_options_btn" style="display: none">'.__('View Options').'</a>
                    </div>';




        $output.= '</div>';
         $output .= $this->widget_after(); // render widget after content

        return $output;
    }

    public function widget_title()
    {
        // TODO: Implement widget_title() method.
        return __('Voting Poll');
    }
}