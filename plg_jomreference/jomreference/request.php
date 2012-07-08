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

define( '_JEXEC', 1 );
define( '_VALID_MOS', 1 );
define( 'DS', DIRECTORY_SEPARATOR );
$path = str_replace(DS.'plugins'.DS.'community'.DS.'jomreference', "", realpath(dirname(__FILE__) .DS));
define( 'JPATH_BASE',  $path );
require_once ( JPATH_BASE .DS.'includes'.DS.'defines.php' );
require_once ( JPATH_BASE .DS.'includes'.DS.'framework.php' );
$mainframe =& JFactory::getApplication('site');
$mainframe->initialise();

if (!isset($_POST['uid'])) die ('Error'); // uid , the requested user's id
if(intval($_POST['uid'])==0) die ('Error');
if (!isset($_POST['req'])) die ('Error'); //request
if (!isset($_POST['lck']) || intval($_POST['lck'])!=1) die ('Restricted access'); // restrict access on insecure call

    //check permission
	function checkPerm(){
	  $database =& JFactory::getDBO();
	  $user =& JFactory::getUser();
	  $database->setQuery( 'select * from #__jomreference_permissions where sourceid='.intval($_POST['uid']).' and targetid ='.$user->id.';');     
      $result = $database->loadAssocList();
	  return $result;
	}
	
	//load component settings
	function getSettings(){
	  $database =& JFactory::getDBO();
	  $database->setQuery( 'select * from #__jomreference_settings;');     
      $result = $database->loadAssocList();
	  return $result;
	}
	
	
  // store request
  function setRequest(){
   $user =& JFactory::getUser();
   if($user->id!=0){
    $settings = getSettings();
 	$database =& JFactory::getDBO();
	$database->setQuery( 'select * from `#__jomreference_requests` where `sourceid`='.$user->id.' and `targetid` ='.intval($_POST['uid']).';');     
    $result = $database->loadAssocList();
	if($result==null){
     $query = " insert into `#__jomreference_requests`(`sourceid`,`targetid`,`reqtext`,`reqdate`) 
     values(".$user->id.",".intval($_POST['uid']).",'".mysql_real_escape_string(notags($_POST['req']))."',NOW());"; 
	 $database->setQuery( $query );
	 $database->query();	
	 if(intval($settings[0]['rec_perm'])!=0) {
	   if(checkPerm()==null){
	    $query = " insert into `#__jomreference_permissions`(`sourceid`,`targetid`,`permdate`,`accept`) 
        values(".intval($_POST['uid']).",".$user->id.",NOW(),1);"; 
	   }else{
	    $query = " update `#__jomreference_permissions` set `accept`=1 where `sourceid`=".$user->id." and `targetid`=".intval($_POST['uid']);
	   }
	    $database->setQuery( $query );
	    $database->query();	
	   }
	 }
	 echo 'Your request is stored.';
	}
   }

  function notags($txt){
    str_replace("&", "&amp;", $txt);
    str_replace("<", "&lt;", $txt);
	str_replace(">", "&gt;", $txt);
   return $txt;
  }
  
  setRequest();
?>