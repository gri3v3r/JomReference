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

if (!isset($_POST['uid'])) die ('Error'); // uid , user's id
if(intval($_POST['uid'])==0) die ('Error');
if (!isset($_POST['ord'])) die ('Error');// ord, select 1 for order by date 2 for order by the requesting user's name
if (!isset($_POST['lck']) || intval($_POST['lck'])!=1) die ('Restricted access'); // restrict access on insecure call

// retrieve permission requests
  function getPermRequests(){
    $i=1;
	$user =& JFactory::getUser();
	if($user->id!=0 && $user->id==intval($_POST['uid'])){
	 $database =& JFactory::getDBO(); 
   	 if((int)$_POST['ord']==1){
	  $database->setQuery( 'select * from `#__jomreference_permissions` where `targetid` ='.intval($_POST['uid']).'  and `accept` = 0 order by `permdate` desc;');
	 }
     else if((int)$_POST['ord']==2){
	  $database->setQuery( 'select perm.* from `#__jomreference_permissions` as `perm` inner join `#__users` as `juse` on `perm`.`sourceid`=`juse.id` where `perm`.`targetid` ='.intval($_POST['uid']).' and `perm`.`accept` = 0  order by  `juse`.`name`;');
	 }
	 else  die ('Error');
     $result = $database->loadAssocList();
     if($result!=null)
	 {
	  foreach($result as $key => $row){
	   $u =& JFactory::getUser($row["sourceid"]);
       echo '<div style="border-style:solid;-moz-border-radius:6px;border-radius:6px;border-width:1px;width:95%;padding:20px;"><span style="font-weight:bold;" >'.$i.'. Permission request by <a href="index.php?option=com_community&view=profile&userid='.$row["sourceid"].'" target="_blank">'.$u->name.'</a> at '.$row["permdate"].' &nbsp;&nbsp;&nbsp;<a href="javascript:void(0)" style="float: right;padding-bottom: 0px;padding-left: 15px;padding-right: 15px;padding-top: 0px;"  onclick="jQuery(\'#permissions\').load(\'plugins/community/jomreference/acceptperm.php\',{lck: 1,uid: '.$row["sourceid"].'});jQuery(\'#permissions\').load(\'plugins/community/jomreference/loadperms.php\',{uid:'.intval($_POST['uid']).',lck: 1,ord:1});return false;">Accept</a></span><br/><br/>';	
  	   echo '</div></div>';
	  $i++;
      }
     }
	 else
	  echo ' No permission request is made yet';
	}
  }
  
getPermRequests();  
?>