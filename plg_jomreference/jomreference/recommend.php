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

if (!isset($_POST['uid'])) die ('Error'); // uid , the recommended user's id
if(intval($_POST['uid'])==0) die ('Error');
if (!isset($_POST['job'])) die ('Error'); // job position
if (!isset($_POST['rec'])) die ('Error'); // recommendation
if (!isset($_POST['rate'])) die ('Error'); // rating
if (!isset($_POST['from'])) die ('Error'); // start year
if(intval($_POST['from'])==0) die ('Error');
if (!isset($_POST['to'])) die ('Error'); // end year
if(intval($_POST['to'])==0) die ('Error');
if (!isset($_POST['empl'])) die ('Error'); // times employed
if (!isset($_POST['lck']) || intval($_POST['lck'])!=1) die ('Restricted access'); // restrict access on insecure call

	//load component settings
	function getSettings(){
	  $database =& JFactory::getDBO();
	  $database->setQuery( 'select * from #__jomreference_settings;');     
      $result = $database->loadAssocList();
	  return $result;
	}

  // store recommendation
  function setRecommendation(){
   $user =& JFactory::getUser();
   $u =& JFactory::getUser(intval($_POST['uid']));
   $settings=getSettings();
   if($user->id!=0){
 	$database =& JFactory::getDBO();
	$database->setQuery( 'select * from `#__jomreference_recommendations` where `sourceid`='.$user->id.' and `targetid` ='.intval($_POST['uid']).';');     
    $result = $database->loadAssocList();
	if($result==null){
	 if(intval($settings[0]['busi_mod'])==1)
	    $bmode = mysql_real_escape_string(notags('Business interaction : '.$u->name.' worked for '.$user->name.' as '.$_POST['job'].' in '.((intval($_POST['from'])==intval($_POST['to']))?intval($_POST['to']):intval($_POST['from']).'-'.intval($_POST['to'])).' and was employed '.$_POST['empl'].' time(s).'));
	  else
	    $bmode='';
     $query = " insert into `#__jomreference_recommendations`(`sourceid`,`targetid`,`rectext`,`recdate`,`rating`,`interaction`) values(".$user->id.",".intval($_POST['uid']).",'".mysql_real_escape_string(notags($_POST['rec']))."',NOW(),".intval($_POST['rate']).",'".$bmode."');"; 
	 $database->setQuery( $query );
	 $database->query();
	 if(intval($settings[0]['rec_req'])==1){
	  $query = "delete from `#__jomreference_requests` where `sourceid`=".intval($_POST['uid'])." and `targetid`=".$user->id.";"; 
	  $database->setQuery( $query );
	  $database->query();	
	 }
	 echo 'Your recommendation is stored.';
	}
   }
  }

  //disable html tags
  function notags($txt){
    str_replace("&", "&amp;", $txt);
    str_replace("<", "&lt;", $txt);
	str_replace(">", "&gt;", $txt);
   return $txt;
  }
  
  setRecommendation();
?>