<?php namespace Sourcebit\Dprimecms\Replies;

class FailedReply implements ReplyInterface {

    public function __construct($message) {

        $this->message = $message;

    }

    public function hasFailed() {

        return true;

    }

    /** 
     * @return mixed 
     */

    public function getReply() {

        return $this->message;

    }
}