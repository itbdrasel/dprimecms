<?php

namespace Sourcebit\Dprimecms\Models;

use Illuminate\Database\Eloquent\Model;

class Rating extends Model
{
    protected $table = 'tbl_rating';
	protected $primaryKey = 'vote_id';
	public $timestamps = false;
    protected $fillable = ['vote', 'a_id', 'ip_address'];


    /* * *
     * Provide Rating number, total Rating avarage.
     * @param array $postId - Id of the post
     * 
     * @return array
     * */

    function scopeGetRating($query, $postId) {

        $ratingsInfo = [];
        $ratings = $query->select()->where(['a_id' => $postId])->get();
    				    
        $numberOfRating = $ratings->count();
        $totalRating = $ratings->sum('vote');        
		$ratingsInfo['ratingNumbers'] = $numberOfRating;
        $rating = 0;

        if ($numberOfRating > 0) {
            $rating = $totalRating / $numberOfRating;
        }

        $ratingsInfo['totalRatings'] = $rating;
        $ratingsInfo['totalRatingsRound'] = round($rating, 1);

        return $ratingsInfo;
    }

    /* * *
     * Insert Rating data to the db.
     * @param array $rateData - Rating data array like vote, post id, ip
     * 
     * @return Boolean
     * */
    public function scopeRateAction($query, $rateData){

        $rateQueryData = [
            'vote' => $rateData['vote'],
            'a_id' => $rateData['postId'],
            'ip_address' => $rateData['ipAddress'],
        ];
        
        if( !$this->isRated( $rateData['postId']) )
            return $query->create($rateQueryData);
        else return false;
    }

    /* * *
     * check whether the post was rated or not
     * @param int $postId - Id of the post
     * 
     * @return Boolean
     * */
    public function scopeIsRated($query, $postId){

        $isRated = $query->where(['a_id' => $postId, 'ip_address'=> get_actual_ip() ])
                        ->first();
        
        if( !empty($isRated) ) return true;
        else return false;
    }
 



}
