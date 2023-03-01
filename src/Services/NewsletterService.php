<?php

namespace Sourcebit\Dprimecms\Services;

use Sourcebit\Dprimecms\Models\Newsletter;
use phpDocumentor\Reflection\Types\Boolean;

class NewsletterService{

    public function __construct(){

    }


     /***
     * Newsletter Subscribe
     * @param array $newsletterData
     *
     * @return Boolean
     **/
    public function subscribe(array $newsletterData){

        $data = [
            'n_email'           => $newsletterData['email'],
            'n_name'            => $newsletterData['name'],
            'n_ip_address'	    => $newsletterData['ip'],
            'n_status'          => 0,
            'n_verf_mail_count'	=> 1,
            'n_score'	        => 0,
            'n_unsbs'	        => 0,
            'n_key'	            => $newsletterData['token'],
            'n_created_at'      => date("Y-m-d H:i:s"),
        ];

       return Newsletter::create($data);
    }


    /***
     * Count Subscriber
     * @param boolean $countVerified return only verified subs number
     *
     * @return integer
     **/
    public function countSubscriber($countVerified = false):int
    {
        if($countVerified)
            return Newsletter::where('n_status', '=', 1)->count();
        else
            return Newsletter::count();
    }


    /***
     * Check whether subscribed or not
     * @param string $email - email address to check
     *
     * @return boolean
     **/
    public function isSubscribed($email){

        return Newsletter::where([
            ['n_email', '=', $email],
        ])->exists();
    }
    /***
     * Get the token by email
     * @param string $email - email address
     *
     * @return string
     **/
    public function getToken($email){
        return Newsletter::where([
            ['n_email', '=', $email],
        ])->value('n_key');

    }

    /***
     * Check the email exist and verified or not
     * @param string $email - email address to check
     *
     * @return boolean
     **/
    public function isVerified($email):bool
    {
        return Newsletter::where([
            ['n_status', '=', 1],
            ['n_email', '=', $email],
        ])->exists();
    }

    public function getVerified($email){
        return Newsletter::where([
            ['n_email', '=', $email],
        ])->update(['n_status' => 1]);
    }

   /***
     * Unsubscribe from newsletter
     * @param string $email - email address
     *
     * @return boolean
     **/
    public function unSubscribe($email){
        return Newsletter::where([
            ['n_email', '=', $email],
        ])->update(['n_unsbs' => 1]);

    }

    /***
     * Limit Sending emails for verifications
     * @param string $email - email address
     *
     * @return boolean
     **/
    public function limitVerifyMailSend($email):bool
    {
        $limit = 10;
        $mailNumber = Newsletter::where('n_email','=', $email)->value('n_verf_mail_count');

        if($mailNumber < $limit ){
            return Newsletter::where('n_email','=', $email)->increment('n_verf_mail_count');
        }else return false;
    }


}
