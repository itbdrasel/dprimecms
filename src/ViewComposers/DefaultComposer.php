<?php

namespace Sourcebit\Dprimecms\ViewComposers;

use Sourcebit\Dprimecms\Models\Menu;
use Illuminate\View\View;
use Content;

class DefaultComposer
{
    protected $menus;

    /**
     * Create a new profile composer.
     *
     * @param  \App\Repositories\UserRepository  $users
     * @return void
     */
    public function __construct(Menu $menus)
    {
        $this->menus = $menus;
    }

    /**
     * Bind data to the view.
     *
     * @param  \Illuminate\View\View  $view
     * @return void
     */
    public function compose(View $view)
    {

        $menus = \Cache::rememberForever('menus', function(){
            $where = ['m_type'=>1, 'm_status'=>'active'];
            return \Sourcebit\Dprimecms\Models\Menu::where($where)->orderBY('m_order','ASC')->get();
        });

        $menus = formatMenu($menus, 'Root' );

        $view->with('menus',  $menus);

        //passing common page meta data if not already set
        if( !$view->meta ){
            $meta = Content::metaData([]);
            $view->with('meta', $meta);
        }


    }
}
