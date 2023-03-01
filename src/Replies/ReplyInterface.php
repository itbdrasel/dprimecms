<?php namespace Sourcebit\Dprimecms\Replies;

interface ReplyInterface {
    
    /* * 
     * @return boolean 
     */

    public function hasFailed();
    
    /* * 
     * @return mixed 
     */
    
     public function getReply();

}