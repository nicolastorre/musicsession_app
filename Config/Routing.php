<?php

use UrlRewriting as url;

/****************************************************************************************************/
// Class Routing to register all the routes of the website in the static array of UrlRewriting Class
/****************************************************************************************************/
class Routing
{
	public static function routingList() {
		url::addURL("authuser","Auth","authuser");
		url::addURL("inscuser","Auth","inscuser");

		url::addURL("Home","Home","index","");
			url::addURL("addNews","Home","addnews");

		url::addURL("Profil","Profil","index");
			url::addURL("Friendship","Profil","friendshipstatus");

		url::addURL("Songslist","Songslist","index");
		url::addURL("Friends","Friends","index");

    	url::addURL("Notifications","Notifications","index");

    	url::addURL("Messages","Messages","index");
    		url::addURL("Discussion","Messages","getdiscussion");
    		url::addURL("SendMsg","Messages","sendmsg");


    	url::addURL("NewSong","NewSong","index");
    		url::addURL("addNewSong","NewSong","addnewsong");

    	url::addURL("Parameters","Parameters","index");
    		url::addURL("updateparameters","Parameters","updateparameters");
	}
}

?>