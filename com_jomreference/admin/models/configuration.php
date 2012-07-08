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
JTable::addIncludePath(JPATH_ADMINISTRATOR.DS.'components'.DS.'com_jomreference'.DS.'tables');


class JomReferenceModelConfiguration extends JModel
{

  function getSetting( $compid )
  {
    $db = $this->getDBO();
	$table = $db->nameQuote( '#__jomreference_settings' );
	$key = $db->nameQuote( 'comp_id' );
	$query = " SELECT * FROM " . $table
	. " WHERE " . $key . " = " . $compid;
	$db->setQuery($query);
	$setting = $db->loadObject();
	if($setting === null)
	{
	  JError::raiseError(500, 'The instance ['.$compid.'] of component is not found.');
	}
	else
	{
	  return $setting;
	}
  }
  
  function store()
  {
	$table=& $this->getTable();

	$setting = JRequest::get('post');

    $table->reset();
   if( !$table->bind($setting))
   {
	$this->setError( $this->_db->getErrorMsg());
	return false;
   }

   if( !$table->store())
   {
    $this->setError( $table->getErrorMsg());
    return false;
   }
   
   return true;
  }
 
 }