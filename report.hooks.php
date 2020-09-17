<?php
    new hook("profileLink", function ($profile) {
    	global $user;
  		if($user->id == $profile->id){
  			return false;
  		}
        return array(
            "url" => "?page=report&id=".$profile->id, 
            "text" => "Report"
        );
    });
