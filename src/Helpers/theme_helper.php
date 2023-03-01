<?php

/*****
* Theme Helper
* Basic functions for frontEnd theme
*/


/* * * *
 * showMainMenu();
 * @param $attributes = ['navAttr', 'subNavAttr', 'itemAttr', 
 * 'itemLinkAttr' 'drowDownAttr', 'dropDownLinkAttr', 'activeItemAttr' ];
 * 
 */


function showMainMenu($menu, $attributes = []){
	//attributes defaults
	if(!array_key_exists('navAttr', $attributes)) 
	$attributes['navAttr'] = 'class="navbar-nav ml-auto"';

	if(!array_key_exists('subNavAttr', $attributes)) 
	$attributes['subNavAttr'] = 'class="dropdown-menu animate__animated animate__fadeIn animate__faster"';

	if(!array_key_exists('itemAttr', $attributes)) 
	$attributes['itemAttr'] = 'class="nav-item"';

	if(!array_key_exists('itemLinkAttr', $attributes)) 
	$attributes['itemLinkAttr'] = 'class="nav-link"';

	if(!array_key_exists('dropDownAttr', $attributes)) 
	$attributes['dropDownAttr'] = 'class="nav-item dropdown"'; 
	
	if(!array_key_exists('dropDownLinkAttr', $attributes)) 
	$attributes['dropDownLinkAttr'] = 'class="nav-link dropdown-toggle" data-toggle="dropdown" href="#"'; 

	if(!array_key_exists('activeItemAttr', $attributes)) 
	$attributes['activeItemAttr'] = 'class="nav-item active"'; 	

	

	 _showMainMenu($menu, $options = [], $attributes );

}
/* * * *
 * _showMainMenu();
 * @param $options = ['subnav' => false, 'active' => '']
 * 
 */
function _showMainMenu($menu, $options = [], $attributes = [] ){

    // options defaults
    if(!array_key_exists('subnav', $options)) $options['subnav'] = false; 
    if(!array_key_exists('active', $options)) $options['active'] = '';	
		
    //nav parents
    if($options['subnav'] == true) echo '<ul '.$attributes['subNavAttr'].' >';
    else echo '<ul '.$attributes['navAttr'].' >';
    
    foreach($menu as $i => $item){
        
        $lnk = $item->m_alias;        
        if($item->m_view === 'category') $lnk = $item->m_link;
        
        if( !empty($item->submenu) ):
            $options['subnav'] = true;
            echo '<li '.$attributes['dropDownAttr'].'><a  '.$attributes['dropDownLinkAttr'].'>' . $item->m_title . ' <span class="caret"></span></a>';
        elseif($item->m_alias === 'home'):
            echo '<li  '.$attributes['itemAttr'].'><a '.$attributes['itemLinkAttr'].' href="'.base_url().'">' . $item->m_title . '</a>';
        elseif($options['active'] === $item->m_alias):
            echo '<li '.$attributes['activeItemAttr'].'><a '.$attributes['itemLinkAttr'].' href="'.base_url($lnk).'">' . $item->m_title . '</a>';
        else:			
            echo '<li '.$attributes['itemAttr'].' ><a '.$attributes['itemLinkAttr'].' href="'.base_url($lnk).'">'.$item->m_title. '</a>';
        endif;
                            
        if( !empty( $item->submenu ) ){												
            _showMainMenu($item->submenu, $options, $attributes);
        }
                                
        echo '</li> ';
    }
    echo '</ul>';				
}

				
 /***
  ** Footer Menu
  **/
 function showFooterMenu($menu, $dropdown = FALSE){
		echo '<ul class="footer-menu">';
		foreach($menu as $i => $item){
				echo ' <li><a href="'.base_url().$item['m_alias'].'">' . $item['m_title'] . '</a>';
			if( !empty($item['submenu']) ){
				showfooterMenu($item['submenu']);
			}
			echo '</li> ';
		}
		echo '</ul>';
 }


 /***
  ** Sidebar Menu
  **/
 function showSideMenu($menu, $dropdown = FALSE){
		echo '<ul class="footer-menu">';
		foreach($menu as $i => $item){
				echo ' <li><a href="'.base_url().$item['m_alias'].'">' . $item['m_title'] . '</a>';
			if( !empty($item['submenu']) ){
				showsideMenu($item['submenu']);
			}
			echo '</li> ';
		}
		echo '</ul>';
 }


 
 /* * * *
  * Return single, multiple, with img tag or without img tag.
  * 
  * @param String $imagePaths - Image paths separated by |-
  * @param Array (Optional) $attributes 
    (indexs) witTag, single, title, class, width, height
  *
  * @return String or Empty
  */

  function viewImage($imagePaths, $attributes = []){			
	
	$withTag = $attributes['withTag'] ?? true; // with <img /> tag
	$single = $attributes['single'] ?? true; // return single image or multiple
	$title = $attributes['title'] ?? '';
	$class = $attributes['class'] ?? '';
	$width = $attributes['width'] ?? '';
	$height = $attributes['height'] ?? '';

	if(preg_match('/\|/', $imagePaths)){		
		$images = explode('|', $imagePaths);
				
		if($single){
			if($withTag) return "<img title='".$title."' src='".url($images[0])."' />";
			else return $images[0];			
		}else{
			$imageTagPath = '';
			foreach($images as $img){
				$imageTagPath.='<div class="col-3"><a data-image="'.url($img).'" href="#"><img class="'.(empty($class) ?? 'img-thumbnail').'" src="'.url($img).'" /></a></div>';
			}
			return $imageTagPath;	
		}								
	}else{
		$src = $imagePaths;
		
		if($withTag){
			return "<img src='".$src."' height='".$height."' width='".$width."' class='".$class."' title='".$title."' />";
		}else{
			return $src;
		}
	}		
 }



 /***
  ** utility Function
  **/
 
 function contentUtility(){
		echo "<div class='con_util'>";
		echo "<ul>";
			echo "<li><a href='".url()->current()."?print=1' target='_blank' rel='nofollow' class=''><i class='fa fa-print'></i></a></li>";
			echo "<li><a href='javascript:void(0)' class='in'><i class='fa fa-search-minus'></i></a></li>";

			echo "<li><a href='javascript:void(0)' class='de'><i class='fa fa-search-plus'></i></a></li>";
		echo "</ul>";

		echo "</div>";

 }


/***
 ** Social Share Function
 **/

function share($content){
	
	if($content->count() > 0 ):
	
	return '<div class="social"><a href="http:twitter.com/share?text='.$content->title.'&url='.postUrl($content->alias, $content->id).'" target="_blank"><i class="fab fa-twitter fa-lg"></i></a>
			<a href="http:www.facebook.com/sharer.php?u='.postUrl($content->alias, $content->id).'" target="_blank"><i class="fab fa-facebook-square fa-lg"></i></a>
			<a href="https:www.linkedin.com/shareArticle?mini=true&url='.postUrl($content->alias, $content->id).'&title='.$content->title.'" target="_blank"><i class="fab fa-linkedin fa-lg"></i></a></div>
			';
	endif;
}


	
 function ads(){
    echo 'No Ads';
}


