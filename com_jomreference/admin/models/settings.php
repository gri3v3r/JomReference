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

defined( '_JEXEC' ) or die( 'Restricted access' );

jimport( 'joomla.application.component.model' );


class JomReferenceModelSettings extends JModel
{
  var $_settings;
 
  function getSettings()
  {
   $db =& $this->getDBO();
   $table = $db->nameQuote( '#__jomreference_settings' );
   $query = "SELECT * FROM " . $table;
   $db->setQuery( $query );
   $this->_settings = $db->loadObjectList();
   return $this->_settings;
  }
  
}