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

if (!isset($_POST['recid'])) die ('Error'); // record id
if(intval($_POST['recid'])==0) die ('Error');
if (!isset($_POST['uid'])) die ('Error'); // uid , user's id
if(intval($_POST['uid'])==0) die ('Error');
if (!isset($_POST['rep'])) die ('Error'); // reply
if (!isset($_POST['lck']) || intval($_POST['lck'])!=1) die ('Restricted access'); // restrict access on insecure call

 // store user's reply to the recommendation
  function setReply(){
   $user =& JFactory::getUser();
   if($user->id!=0 && $user->id==intval($_POST['uid'])){
	$database =& JFactory::getDBO();
    $database->setQuery( "update `#__jomreference_recommendations` set `replytext`='".mysql_real_escape_string(notags($_POST['rep']))."' where `recid` =".intval($_POST['recid'])." and `targetid`=".intval($_POST['uid']).";" );	
	$database->query();	
   }
  }

   //disable html tags
   function notags($txt){
    str_replace("&", "&amp;", $txt);
    str_replace("<", "&lt;", $txt);
	str_replace(">", "&gt;", $txt);
	return $txt;
  }
  
  setReply();
?>