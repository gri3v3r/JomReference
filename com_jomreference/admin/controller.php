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
jimport( 'joomla.application.component.controller' );

class JomReferenceController extends JController
{

 function display()
 {
  $view =& $this->getView( 'settings', 'html' );
  $model =& $this->getModel( 'settings' );
  $view->setModel( $model, true );
  $view->display();
 }

 function edit()
 {
   $cids = JRequest::getVar('cid', null, 'default', 'array');
   if( $cids === null )
   {
     JError::raiseError( 500,'cid parameter missing from the request' );
   }
   $compId = (int)$cids[0];
   $view =& $this->getView( JRequest::getVar( 'view', 'configuration' ), 'html' );
   $model =& $this->getModel( 'configuration' );
   $view->setModel( $model, true );
   $view->edit( $compId );
  }
  
  function save()
  {
   $model =& $this->getModel( 'configuration' );
   $model->store();
   $redirectTo = JRoute::_('index.php?option='
   .JRequest::getVar('option')
   .'&task=display');
   $this->setRedirect( $redirectTo, 'Settings Saved' );
  }
}
