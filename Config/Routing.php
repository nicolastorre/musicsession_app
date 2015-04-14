<?php

use UrlRewriting as url;

/****************************************************************************************************/
// Class Routing to register all the routes of the website in the static array of UrlRewriting Class
/****************************************************************************************************/
class Routing
{
       
	public static function routingList() {
            
        url::addURL("authpage","Auth","index");
		  url::addURL("authuser","Auth","authuser");
		  url::addURL("inscuser","Auth","inscuser");
          url::addURL("Confirm","Auth","confirmmail");

        url::addURL("Forgotpwd","Auth","forgotpwd");
            url::addURL("sendmail","Auth","sendmail");
            url::addURL("Resetpwd","Auth","reset");

		url::addURL("Home","Home","index","");
			url::addURL("addNews","Home","addnews");

		url::addURL("Profil","Profil","index");
			url::addURL("Friendship","Profil","friendshipstatus");

		url::addURL("Songslist","Songslist","index");
			url::addURL("Delete","Songslist","delete");
			url::addURL("Add","Songslist","add");
                        url::addURL("Share","Songslist","share");

		url::addURL("Tune","Tune","index");
                    url::addURL("Addscore","Tune","addscore");
                    url::addURL("Savescore","Tune","savescore");
                    url::addURL("Deletescore","Tune","deletescore");

		url::addURL("Friends","Friends","index");

    	url::addURL("Notifications","Notifications","index");

    	url::addURL("Messages","Messages","index");
            url::addURL("Discussion","Messages","getdiscussion");
            url::addURL("SendMsg","Messages","sendmsg");
            url::addURL("MsgLoader","Messages","loader");
            url::addURL("Msgsender","Messages","sendmsgajax");

    	url::addURL("NewSong","NewSong","index");
            url::addURL("addNewSong","NewSong","addnewsong");

        url::addURL("Search","Search","index");
        
    	url::addURL("Parameters","Parameters","index");
            url::addURL("updateparameters","Parameters","updateparameters");
            url::addURL("updatephoto","Parameters","updatephoto");
            url::addURL("updatepassword","Parameters","updatepwd");
            url::addURL("logout","Parameters","logout");

        url::addURL("Backoffice","Backoffice","index");
	}
        
        public static function srcList() {
                url::addSRC("tmp", "Ressources/public/tmp/");
                url::addSRC("userfolder", "Ressources/public/data/Users/");
                url::addSRC("imgapp", "Ressources/public/images/app/");
        }
}

?>