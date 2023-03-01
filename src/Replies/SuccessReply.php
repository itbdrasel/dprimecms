<?php namespace Sourcebit\Dprimecms\Replies;

class SuccessReply implements ReplyInterface {
    
    public function __construct($result) {

        $this->result = $result;

    }

    public function hasFailed() {

        return false;

    }

    /** 
     * @return mixed 
     */

    public function getReply() {

        return $this->result;

    }
}