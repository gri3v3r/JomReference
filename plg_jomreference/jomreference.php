<?php
//    JomReference
//    Copyright (C) 2012
//
//    This program is free software: you can redistribute it and/or modify
//    it under the terms of the GNU General Public License as published by
//    the Free Software Foundation, either version 3 of the License, or
//    (at your option) any later version.
//
//    This program is distributed in the hope that it will be useful,
//    but WITHOUT ANY WARRANTY; without even the implied warranty of
//    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
//    GNU General Public License for more details.
//
//    You should have received a copy of the GNU General Public License
//    along with this program.  If not, see <http://www.gnu.org/licenses/>.

defined ('_JEXEC') or die ('Restricted Access');
require_once( JPATH_ROOT.DS.'components'.DS.'com_community'.DS.'libraries'.DS.'core.php');

class plgCommunityJomreference extends CApplications
{
    protected $display; // what will be shown by the plugin
	protected $total_recs; //number of recommendations
	protected $total_reqs; //number of requests
	protected $total_preqs; //number of permission requests
	protected $average_rate; // average rating

	//javascript functions for filling pop up window ,reveal/hide information in it 
	function addjs($settings){
	$user =& JFactory::getUser();
	  if(intval($settings[0]['rate'])!=0){
	   $rating = '<br/><center>Rating: <input type=\"radio\" name=\"ratings\" value=\"1\" checked=\"checked\">1<input type=\"radio\" name=\"ratings\" value=\"2\">2 <input type=\"radio\" name=\"ratings\" value=\"3\">3<input type=\"radio\" name=\"ratings\" value=\"4\">4<input type=\"radio\" name=\"ratings\" value=\"5\">5</center><br/>';
	   $rate='jQuery(\'input[name=ratings]:checked\').val()';
	  }else{
	    $rating ='';
		$rate='';
	  }
	  if(intval($settings[0]['busi_mod'])!=0){
	    $yearlist1='<select id=\"from\">';
		$yearlist2='<select id=\"to\">';
		$employed='<select id=\"empl\">';
		for($i=1950;$i<=2100;$i++){
		 $yearlist1 .= '<option>'.$i.'</option>';
		 $yearlist2 .= '<option>'.$i.'</option>';
		}
		$employed.= '<option>1</option>';
		$employed.= '<option>2</option>';
		$employed.= '<option>3</option>';
		$employed.= '<option>4+</option>';
		 $yearlist1.='</select>';
		$yearlist2.='</select>';
		$employed.='</select>';
		$business='<br/><center>Worked as : <input type=\"text\" id=\"jobtext\"> Period : From:'.$yearlist1.' To:'.$yearlist2.' Employed : '.$employed.'times</center><br/>';
	    $bmode=',job: jQuery(\'#jobtext\').val(),from: jQuery(\'#from\').val(),to: jQuery(\'#to\').val(),empl: jQuery(\'#empl\').val()';
	  }else{
	    $business='';
		$bmode=",job: 1,from: 1,to: 1,empl: 1";
	  }
	  $this->display.= '<script type="text/javascript"> 

	  function managetext(divid){
		if( document.getElementById(divid).style.display == "block")
			document.getElementById(divid).style.display = "none";
		else
			document.getElementById(divid).style.display = "block";
	  }
	  
     function FillWindow(){
		document.getElementById("cWindowContent").innerHTML = "<a id=\"recbydate\" onclick=\"jQuery(\'#recommendations\').load(\'plugins/community/jomreference/loadrecs.php\',{uid:'.intval($_GET['userid']).',lck: 1,ord:1});return false;\" href=\"javascript:void(0)\">List by Date</a>&nbsp;/&nbsp;<a id=\"recbyname\" onclick=\"jQuery(\'#recommendations\').load(\'plugins/community/jomreference/loadrecs.php\',{uid:'.$_GET['userid'].',lck: 1,ord:2});return false;\" href=\"javascript:void(0)\">List by Name</a><br /><br /><div id=\"recommendations\"></div>";
		jQuery(\'#recommendations\').load(\'plugins/community/jomreference/loadrecs.php\',{uid:'.intval($_GET['userid']).',lck: 1,ord:1});
	}';
	if(intval($settings[0]['busi_mod'])!=0)
	$this->display.= '
	function FillCareerWindow(){
		document.getElementById("cWindowContent").innerHTML = "<div id=\"professions\"></div>";
		jQuery(\'#professions\').load(\'plugins/community/jomreference/loadinfo.php\',{uid:'.intval($_GET['userid']).',lck: 1});
	}';
	if(intval($settings[0]['busi_mod'])!=0 && intval($_GET['userid'])==$user->id)
	$this->display.= '
    function FillInfoWindow(){
		document.getElementById("cWindowContent").innerHTML = "<center><div style=\"border-style:solid;-moz-border-radius:6px;border-radius:6px;border-width:1px;width:95%;padding:20px;\" id=\"infobox\">Job position: <input type=\"text\" id=\"proftext\" style=\"background-color:white;color:black;width:250px;\"><br/><br/>Workplace: <input type=\"text\" id=\"placetext\" style=\"background-color:white;color:black;width:250px;\"><br /><br/>From'.$yearlist1.' To'.$yearlist2.'<br/><br /><button id=\"btnSend\" onclick=\"jQuery(\'#infobox\').load(\'plugins/community/jomreference/info.php\',{lck: 1,job: jQuery(\'#proftext\').val(),from: jQuery(\'#from\').val(),to: jQuery(\'#to\').val(),place: jQuery(\'#placetext\').val(),uid: '.intval($_GET['userid']).'});return false;\" style=\"background-color:black;color:white;\">Send</button></center></div>";
	}';
	
	if(intval($settings[0]['rec_req'])!=0 && intval($_GET['userid'])==$user->id)
	$this->display.= '
	function FillReqsWindow(){
		document.getElementById("cWindowContent").innerHTML = "<a id=\"reqbydate\" onclick=\"jQuery(\'#requests\').load(\'plugins/community/jomreference/loadreqs.php\',{uid:'.intval($_GET['userid']).',lck: 1,ord:1});return false;\" href=\"javascript:void(0)\">List by Date</a>&nbsp;/&nbsp;<a id=\"reqbyname\" onclick=\"jQuery(\'#requests\').load(\'plugins/community/jomreference/loadreqs.php\',{uid:'.$_GET['userid'].',lck: 1,ord:2});return false;\" href=\"javascript:void(0)\">List by Name</a><br /><br /><div id=\"requests\"></div>";
		jQuery(\'#requests\').load(\'plugins/community/jomreference/loadreqs.php\',{uid:'.intval($_GET['userid']).',lck: 1,ord:1});
	} ';

	if(intval($settings[0]['rec_perm'])!=0 && intval($_GET['userid'])==$user->id)
	$this->display.= '
	 function FillPermsWindow(){
		document.getElementById("cWindowContent").innerHTML = "<a id=\"permbydate\" onclick=\"jQuery(\'#permissions\').load(\'plugins/community/jomreference/loadperms.php\',{uid:'.intval($_GET['userid']).',lck: 1,ord:1});return false;\" href=\"javascript:void(0)\">List by Date</a>&nbsp;/&nbsp;<a id=\"permbyname\" onclick=\"jQuery(\'#permissions\').load(\'plugins/community/jomreference/loadperms.php\',{uid:'.$_GET['userid'].',lck: 1,ord:2});return false;\" href=\"javascript:void(0)\">List by Name</a><br /><br /><div id=\"permissions\"></div>";
		jQuery(\'#permissions\').load(\'plugins/community/jomreference/loadperms.php\',{uid:'.intval($_GET['userid']).',lck: 1,ord:1});
	}';
	
	$this->display.= '
	function FillRecWindow(){
		document.getElementById("cWindowContent").innerHTML = "<div style=\"border-style:solid;-moz-border-radius:6px;border-radius:6px;border-width:1px;width:95%;padding:20px;\" id=\"recommendbox\"><textarea style=\"width:100%;height:400px;background-color:white;color:black;\" id=\"recommendtext\"></textarea><br />'.$business.$rating.'<button id=\"btnSend\" onclick=\"jQuery(\'#recommendbox\').load(\'plugins/community/jomreference/recommend.php\',{lck: 1,rec: jQuery(\'#recommendtext\').val(),uid: '.intval($_GET['userid']).',rate : '.((intval($settings[0]['rate'])!=0)?$rate:'0').$bmode.'});return false;\" style=\"background-color:black;color:white;\">Send</button></div>";
	}
	
    function FillReqWindow(){
		document.getElementById("cWindowContent").innerHTML = "<div style=\"border-style:solid;-moz-border-radius:6px;border-radius:6px;border-width:1px;width:95%;padding:20px;\" id=\"reqbox\"><textarea style=\"width:100%;height:400px;background-color:white;color:black;\" id=\"requesttext\"></textarea><br /> <button id=\"btnSend\" onclick=\"jQuery(\'#reqbox\').load(\'plugins/community/jomreference/request.php\',{lck: 1,req: jQuery(\'#requesttext\').val(),uid: '.intval($_GET['userid']).'});return false;\" style=\"background-color:black;color:white;\">Send</button></div>";
	}
    </script>';
   }
	
	//load number of recommendations of user
	function getRecCount($settings,$user)
	{
      $database =& JFactory::getDBO(); 
	  if(intval($settings[0]['user_man'])==1 && $user->id!=intval($_GET['userid']))
	   $publish = " and `show`=1";
      else
	   $publish = "";
	  $database->setQuery( 'select count(*) as reccount from `#__jomreference_recommendations` where targetid ='.intval($_GET['userid']).$publish.';'  );     
      $result = $database->loadAssocList();
      $this->total_recs = $result[0]['reccount'];
	}
	
	//load number of permission requests to user
	function getPermCount()
	{
      $database =& JFactory::getDBO();  
	  $database->setQuery('select count(*) as permcount from `#__jomreference_permissions` where targetid ='.intval($_GET['userid']).' and accept=0;');     
      $result = $database->loadAssocList();
      $this->total_preqs = $result[0]['permcount'];
	}
	
	//load most recent job info
	function getInfo() {
	  $database =& JFactory::getDBO();  
	  $database->setQuery('select * from `#__jomreference_businessinfo` where `userid` ='.intval($_GET['userid']).' and yrto in(select max(yrto) from `#__jomreference_businessinfo` where `userid` ='.intval($_GET['userid']).');');     
      $result = $database->loadAssocList();
	  return $result;
	}
	
	//load average rating of user
	function getAvgRating($settings)
	{
      $database =& JFactory::getDBO(); 
      if($settings==1)	  
	    $publish = ' and `show`>0';
	  else
	    $publish='';
	  $database->setQuery('SELECT TRUNCATE(AVG(`rating`),1) As `rating`, COUNT(*) AS `total` FROM `#__jomreference_recommendations` where `targetid`='.intval($_GET['userid']).' and `rating`>0'.$publish.';');     
      $result = $database->loadAssocList();
	  if($result[0]['rating']!=null)
       $this->average_rate = $result[0]['rating'].' / 5.0<br/>(Rated by '.$result[0]['total'].' user(s))';
	  else
	   $this->average_rate = '-';
	}
	
	//load number of requests to user
	function getReqCount()
	{
      $database =& JFactory::getDBO();  
	  $database->setQuery( 'select count(*) as `reqcount` from `#__jomreference_requests` where `targetid` ='.intval($_GET['userid']).';'   );     
      $result = $database->loadAssocList();
      $this->total_reqs = $result[0]['reqcount'];
	}
	
	//check if the user has already recommended
	function checkRec(){
	  $database =& JFactory::getDBO();
	  $user =& JFactory::getUser();
	  $database->setQuery( 'select * from `#__jomreference_recommendations` where `sourceid`='.$user->id.' and `targetid` ='.intval($_GET['userid']).';');     
      $result = $database->loadAssocList();
	  return $result;
	}
	
	//check if the user has already made a request
	function checkReq(){
	  $database =& JFactory::getDBO();
	  $user =& JFactory::getUser();
	  $database->setQuery( 'select * from `#__jomreference_requests` where `sourceid`='.$user->id.' and `targetid`='.intval($_GET['userid']).';');   
      $result = $database->loadAssocList();
	  if($result==null) {
	     $database->setQuery( 'select * from `#__jomreference_recommendations` where `sourceid`='.intval($_GET['userid']).' and `targetid` ='.$user->id.';');   
         $result = $database->loadAssocList(); 
		 return $result;
	  }
      return $result; 
	}
	
	//check if the user has already made a permission request
	function checkPerm(){
	  $database =& JFactory::getDBO();
	  $user =& JFactory::getUser();
	  $database->setQuery( 'select * from `#__jomreference_permissions` where `sourceid`='.$user->id.' and `targetid`='.intval($_GET['userid']).';');     
      $result = $database->loadAssocList();
	  return $result;
	}

	//load component settings
	function getSettings(){
	  $database =& JFactory::getDBO();
	  $database->setQuery( 'select * from `#__jomreference_settings`;');     
      $result = $database->loadAssocList();
	  return $result;
	}
	
	//check if the component is installed
	function checkComponent(){
	  $database =& JFactory::getDBO();
	  $user =& JFactory::getUser();
	  $database->setQuery( "select * from `#__components` where `name`='JomReference';");     
      $result = $database->loadAssocList();
	  return $result;
	}
	
    function plgCommunityJomreference(&$subject, $option)
    {
        parent::__construct ($subject, $option);
    }


    //display on plugin's space
    function onProfileDisplay ()
    {
	    $u =& JFactory::getUser(intval($_GET['userid']));
		$user =& JFactory::getUser();
		$pflag = 1;
		if($this->checkComponent()!=null)
		{
		 $settings = $this->getSettings();
         $this->addjs($settings);
		 $this->getRecCount($settings,$user);
		 if(intval($settings[0]['busi_mod'])!=0 || intval($settings[0]['rate'])!=0){
		  $info = $this->getInfo();
		  if($info!=null || intval($settings[0]['rate'])!=0)
		    $this->display.='<div style="border-style:solid;-moz-border-radius:6px;border-radius:6px;border-width:1px;padding:10px;color:black;overflow:auto;">';
		 }
		 if(intval($settings[0]['busi_mod'])!=0){
		  if($info!=null){
		    $this->display.='<span style="font-weight:bold;text-decoration:underline;">Business Info:</span><br/><br/><b>Job position :</b>&nbsp;&nbsp;'.$info[0]['profession'].'<br/><b>Workplace :</b>&nbsp;&nbsp;'.$info[0]['workplace'].'<br/><b>Period :</b>&nbsp;&nbsp;'.(($info[0]['yrfrom']==$info[0]['yrto'])?$info[0]['yrto']:$info[0]['yrfrom'].'-'.$info[0]['yrto']);
			if(intval($settings[0]['rate'])!=0)
			  $this->display.='<br/>';
		  }
		 }
		 if(intval($settings[0]['rate'])!=0){
		  $this->getAvgRating($settings[0]['user_man']);
		  $this->display.='<b>Average rating :</b>&nbsp;&nbsp;'.$this->average_rate;
		 }
		  if(intval($settings[0]['busi_mod'])!=0 || intval($settings[0]['rate'])!=0)
		 $this->display.='</div>';
		 $this->display.='<ul>';
		 if(intval($settings[0]['busi_mod'])!=0){
		    $this->display.='<li><a href="javascript: void(0)" onclick="cWindowShow(\'FillCareerWindow()\',\'Career info for '.$u->name.'\', 800, 500);">Check career information</a></li>';
		 }
         $this->display.='<li><a href="javascript: void(0)" onclick="cWindowShow(\'FillWindow()\',\'Recommendations for '.$u->name.'\', 800, 500);">Recommendations ('.$this->total_recs.')</a></li>';
    	 if($u->id==$user->id){
		   if(intval($settings[0]['rec_req'])!=0){
		     $this->getReqCount();
		     $this->display.='<li><a href="javascript: void(0)" onclick="cWindowShow(\'FillReqsWindow()\',\'Recommendation requests for '.$u->name.'\', 800, 500);">Recommendation requests ('.$this->total_reqs.')</a></li>';
		   }
		   if(intval($settings[0]['rec_perm'])!=0){
		     $this->getPermCount();
		     $this->display.='<li><a href="javascript: void(0)" onclick="cWindowShow(\'FillPermsWindow()\',\'Permission requests for '.$u->name.'\', 800, 500);">Permission requests ('.$this->total_preqs.')</a></li>';
		   }
		   if(intval($settings[0]['busi_mod'])!=0){
		     $this->display.='<li><a href="javascript: void(0)" onclick="cWindowShow(\'FillInfoWindow()\',\'New career info for '.$u->name.'\', 800, 500);">Add new career information</a></li>';
		   }
		 }
		 if($user->id!=0 && $u->id!=$user->id){
		  if(intval($settings[0]['rec_req'])!=0){
		   if($this->checkReq()==null){
		    $this->display.='<li id="reqlink"><a href="javascript: void(0)" onclick="cWindowShow(\'FillReqWindow()\',\'Request a recommendation from '.$u->name.'\', 800, 500);">Request a recommendation</a></li>';
		   }
		  }
		  if(intval($settings[0]['rec_perm'])!=0) {
		   if($this->checkPerm()==null){
		     $this->display.='<li id="permlink"><a href="javascript: void(0)" onclick="jQuery(\'#permlink\').load(\'plugins/community/jomreference/permission.php\',{lck: 1,uid: '.intval($_GET['userid']).'});">Ask for recommendation permission</a></li>';
		   }
		  }
		  if($this->checkRec()==null){
		    if(intval($settings[0]['rec_perm'])!=0) {
			   $res = $this->checkPerm();
		       if($res==null){
			      $pflag=0;
			   }
			   else{
			    if(intval($res[0]['accept'])==0)
				  $pflag=0;
			   }	
			}
		     if($pflag==1)
			 {
		       $this->display.='<li id="reclink"><a href="javascript: void(0)" onclick="cWindowShow(\'FillRecWindow()\',\'Make a recommendation for '.$u->name.'\', 800, 500);">Make a recommendation</a></li>';
			 }
		  }
		  $this->display.='</ul>';
	     }
		}
		else{
		 $this->display = 'Not available.';
		}
		return $this->display;
     }
}

?>


