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
if (!isset($_POST['lck']) || intval($_POST['lck'])!=1) die ('Restricted access'); // restrict access on insecure call

// retrieve career info
  function getBusinessInfo(){
    $i=1;
	$user =& JFactory::getUser();
	$database =& JFactory::getDBO(); 
	$database->setQuery( 'select * from `#__jomreference_businessinfo` where `userid` ='.intval($_POST['uid']).'  order by `yrto` desc;');
    $result = $database->loadAssocList();
    if($result!=null)
	{
	 foreach($result as $key => $row){
      echo '<div style="border-style:solid;-moz-border-radius:6px;border-radius:6px;border-width:1px;width:95%;padding:20px;"><b>'.$i.'. Job position:</b> '.$row["profession"].'&nbsp;&nbsp;&nbsp;&nbsp;<b>Workplace:</b> '.$row["workplace"].' &nbsp;&nbsp;&nbsp;&nbsp;<b>Period:</b> '.(($row['yrfrom']==$row['yrto'])?$row['yrto']:$row['yrfrom'].'-'.$row['yrto']).'</div>';	
	 $i++;
     }
    }
	else
	 echo ' Nothing is added yet.';
  }
  
getBusinessInfo();  
?>