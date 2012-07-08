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
if (!isset($_POST['ord'])) die ('Error'); // ord, select 1 for order by date 2 for order by recommendator's name
if (!isset($_POST['lck']) || intval($_POST['lck'])!=1) die ('Restricted access'); // restrict access on insecure call

   //load component settings
   function getSettings(){
	  $database =& JFactory::getDBO();
	  $database->setQuery( 'select * from `#__jomreference_settings`;');     
      $result = $database->loadAssocList();
	  return $result;
   }
   
   //user recommendation management option
   function publish($recid,$uid,$show){
     $settings=getSettings();
	 if(intval($settings[0]['user_man'])==1)
	 {
	   $publish = '&nbsp;&nbsp;<div id="publnk'.$recid.'"><a href="javascript:void(0)" onclick="jQuery(\'#publnk'.$recid.'\').load(\'plugins/community/jomreference/publish.php\',{uid:'.intval($_POST['uid']).',lck: 1,recid: '.$recid.',show: '.intval($show).'});return false;" >'.(intval($show)==0?'Publish':'Unpublish').'</a></div>';
	 }
	 else
	   $publish = '';
	 return $publish;
   }
   
   //delete recommendation option
   function withdraw($recid,$uid){
    $settings=getSettings();
	if(intval($settings[0]['rec_del'])==1)
	  $withdraw = '&nbsp;&nbsp;<a href="javascript:void(0)" onclick="jQuery(\'#recommendations\').load(\'plugins/community/jomreference/delete.php\',{uid:'.$uid.',lck: 1,recid: '.$recid.'});jQuery(\'#recommendations\').load(\'plugins/community/jomreference/loadrecs.php\',{uid:'.intval($_POST['uid']).',lck: 1,ord:1});return false;" >Withdraw</a>';
	else
	  $withdraw ='';
	 return $withdraw;
   }

  //retrieve entries
  function getRecommendations(){
    $i=1;
	$user =& JFactory::getUser();
	$database =& JFactory::getDBO(); 
	$settings = getSettings();
	if(intval($settings[0]['user_man'])==1 && $user->id!=intval($_POST['uid']))
	  $publish = " and `show`=1";
    else
	  $publish = "";
	if((int)$_POST['ord']==1){
	  $database->setQuery( 'select * from `#__jomreference_recommendations` where `targetid` ='.intval($_POST['uid']).$publish.' order by `recdate` desc;');
	}
    else if((int)$_POST['ord']==2){
	  $database->setQuery( 'select ref.* from `#__jomreference_recommendations` as `ref` inner join jos_users as `juse` on `ref`.`sourceid`=`juse`.`id` where `ref`.`targetid` ='.intval($_POST['uid']).' order by `juse`.`name`;');
	}
	else  die ('Error');
   $result = $database->loadAssocList();
   if($result!=null){
    foreach($result as $key => $row){
	  $u =& JFactory::getUser($row["sourceid"]);
      echo '<div style="border-style:solid;-moz-border-radius:6px;border-radius:6px;border-width:1px;width:95%;padding:20px;"><span style="font-weight:bold;" >'.$i.'. Recommendation by <a href="index.php?option=com_community&view=profile&userid='.$row["sourceid"].'" target="_blank">'.$u->name.'</a> at '.$row["recdate"].' &nbsp;&nbsp;&nbsp;<div style="float: right;padding-bottom: 0px;padding-left: 15px;padding-right: 15px;padding-top: 0px;" ><a href="javascript:void(0)" onclick="managetext(\'reccontent'.$row["recid"].'\')">View</a>'.((intval($row["sourceid"])==$user->id)?withdraw($row["recid"],$user->id):'').((intval($row["targetid"])==$user->id)?publish($row["recid"],$user->id,$row["show"]):'').'</div></span><br/><br/><div id="reccontent'.$row["recid"].'" style="display:none;"><div id="rectxt'.$row["recid"].'" style="border-style:solid;-moz-border-radius:6px;border-radius:6px;border-width:2px;width:95%;padding:10px;">'.$row["rectext"].'</div><br/>';
	  if(intval($settings[0]['busi_mod'])==1){
	    echo'<span style="font-weight:bold;" >'.$row["interaction"].'</span><br/><br/>';
	  }
	  if(intval($settings[0]['rate'])==1){
	    echo'<span style="font-weight:bold;" >Rating :&nbsp;&nbsp;'.$row["rating"].' / 5</span><br/><br/>';
	  }
	  if(strcmp($row["replytext"],"")!=0){
	   echo '<span style="font-weight:bold;" >Reply:</span><br/><div id="repcontent'.$row["recid"].'"><div id="reptxt'.$row["recid"].'" style="border-color:#135CAE;border-style:solid;-moz-border-radius:6px;border-radius:6px;border-width:2px;width:95%;padding:10px;">'.$row["replytext"].'</div></div>';
	  }else{
	   if($user->id == $row["targetid"]){
	    echo '<span style="font-weight:bold;" >Reply:</span><br/><div id="repcontent'.$row["recid"].'"><textarea style"background-color:white;color:black;" id="txtReply'.$row["recid"].'"></textarea><br /> <button id="btnSend"'." onclick=\"jQuery('#repcontent".$row["recid"]."').load('plugins/community/jomreference/reply.php',{lck: 1,rep: jQuery('#txtReply".$row["recid"]."').val(),uid :".$row["targetid"].",recid: ".$row["recid"]."});jQuery('#recommendations').load('plugins/community/jomreference/loadrecs.php',{uid:".intval($_POST['uid']).",lck: 1,ord:1});return false;\" style=\"background-color:black;color:white;\">Send</button></div>";
	   }
	  }
	 echo '</div></div>';
	 $i++;
    }
   }
   else
    echo 'No recommendation is made yet.';
 }
  
  getRecommendations();
?>

