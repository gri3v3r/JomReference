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

class TableConfiguration extends JTable
{
	var $comp_id = 0;
	var $rec_perm = 0;
	var $busi_mod = 0;
	var $rec_del = 0;
	var $rate = 0;
	var $user_man = 0;
    var $rec_req = 0;
	
   function __construct( &$db )
   {
     parent::__construct('#__jomreference_settings','comp_id',$db);
   }
}