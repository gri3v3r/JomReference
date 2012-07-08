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
jimport( 'joomla.application.component.view' );

class JomReferenceViewConfiguration extends JView
{

  function edit($id)
  {
    JToolBarHelper::title(JText::_('JomReference Configuration')
	.': [<small>Edit</small>]');
	JToolBarHelper::save();
	JToolBarHelper::cancel('cancel', 'Close');
	$model =& $this->getModel();
	$setting = $model->getSetting( $id );
	$this->assignRef('setting', $setting);
	parent::display();
  }
}