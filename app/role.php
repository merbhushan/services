<?php

namespace App;
use Illuminate\Database\Eloquent\Model;
use App\url;
use App\urlGroup;

class role extends Model
{
    // Url Parameters which will be fetched.
    public $arrUrlParams = ['id','title','path','component','parent_id','seq_no', 'type', 'status'];
    
    public function index(){

    }

    // Relationship with urls
    public function urls(){
        return $this->belongsToMany('App\url');
    }
    // Relationship with url_groups
    public function groups(){
        return $this->belongsToMany('App\urlGroup');
    }

    /**
     * This function will set a list of front end urls for particular user.
     */
    public function frontEndUrls(){
        // Fetch Urls directly linked with role
        $objFrontEndUrls = $this->urls()->select($this->arrUrlParams)->get();
        
        // Fetch Urls linked with groups
        foreach($this->groups as $group){
        if($group->status)
            $objFrontEndUrls = $objFrontEndUrls->concat($group->urls()->select($this->arrUrlParams)->get());
        }
        // Set front end urls in objFrontEndUrls variable and remove inactive urls.
        $objFrontEndUrls = $objFrontEndUrls->unique('id')->where('status', '=', 1);
        // add parent urls for user urls
        return $this->getFrontEndParentUrls($objFrontEndUrls)->sortBy('id')->sortBy('seq_no');
    }

    /**
     * This function will return a front end parent urls collect & update a parent urls if it's not present in objFrontEndUrls.
     */
    public function getFrontEndParentUrls($objUrls){
        $blnIsProcessed = false;
        
        if(!empty($objUrls)){
            // process a job to get all parent urls object
            while(!$blnIsProcessed){
                // get child url object from urls
                $objChildUrls = $objUrls->where('parent_id', '!=', null);
        
                // get missed parent urls id
                $arrUrlsId = $objUrls->pluck('id');
                $arrChildUrlsId = $objChildUrls->pluck('parent_id')->unique();
                
                $arrMissedParentUrls = $arrChildUrlsId->diff($arrUrlsId);
        
                // fetch missed parent urls
                if(count($arrMissedParentUrls)){
                    
                    $objUrls = $objUrls->concat(\App\url::select($this->arrUrlParams)->where('status', 1)->find($arrMissedParentUrls));
                }
                else{
                    $blnIsProcessed = true;
                }
            }
            return $objUrls;
        }
        return 0;
    }
}
