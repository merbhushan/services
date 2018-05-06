<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\role;

class userAccessManager extends Controller
{
    public $objFrontEndUrls, $objFrontEndParentUrls, $blnIsParenChildRelationGenrated, $blnIsReactUrlObjGenerated;

    /**
     * This function will set a list of front end urls for particular user.
     */
    public function frontEndUrls(role $objRole){
        if($objRole->id){
            if($this->objFrontEndUrls){
                return $this->objFrontEndUrls;
            }
            
            // get & set front end urls based on user role
            $this->objFrontEndUrls = $objRole->frontEndUrls();
            // return front end urls
            return $this->objFrontEndUrls;
        }
        // return blank collection if user id is not set
        return 0;
    }

    public function getParentUrls(role $objRole){
        // dd($objRole);
        if($objRole->id){
            if(empty($this->objFrontEndUrls)){
                $this->frontEndUrls($objRole);
            }
            return $this->objFrontEndParentUrls = $this->objFrontEndUrls->where('parent_id', '=', null)->sortBy('id')->sortBy('seq_no');;
        }
        return 0;
    }

    /**
     * 
     */
    public function generateParentChildRelationalObject(){
        if(!empty($this->objFrontEndParentUrls)){
            // Check is React Url Object is Generated or not. If not generated then generate it.
            if(empty($this->blnIsParenChildRelationGenrated)){
                // set flag to true
                $this->blnIsParenChildRelationGenrated = 1;
                // genereate React Url Object
                foreach($this->objFrontEndParentUrls as $objParentUrl){
                    $objParentUrl -> child = $this->generateChildUrlsObject($objParentUrl);
                }
            }
            return $this->objFrontEndParentUrls;
        }
    }

    /**
     * This function will generate a chid urls object for parent.
     */
    private function generateChildUrlsObject(&$objParentUrl){
        // get a child urls of parent url from url object
        $objChildren = $this->objFrontEndUrls->where('parent_id',$objParentUrl->id)->sortBy('id')->sortBy('seq_no');
        // if parent has a child then set is_parent flag to 1 and generate child url object for each child
        if(count($objChildren)){
            $objParentUrl->is_parent = 1;
            foreach($objChildren as $objChild){
                $objChild->child = $this->generateChildUrlsObject($objChild);
            }
        }
        return $objChildren;
    }

    public function getLeftMenuBarObject(){
        if(!empty($this->objFrontEndParentUrls) && $this->blnIsParenChildRelationGenrated){
            
        }
    }
}
