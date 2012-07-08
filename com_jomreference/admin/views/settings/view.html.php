<?php

defined( '_JEXEC' ) or die( 'Restricted access' );
jimport( 'joomla.application.component.view' );

class JomReferenceViewSettings extends JView
{

  function display( $tpl = null )
  {
   JToolBarHelper::title(JText::_('JomReference Settings'),'/components/com_jomreference/images/JomReference.png');
   JToolBarHelper::editListX();
   $model =& $this->getModel( 'settings' );
   $settings =& $model->getSettings();
   $this->assignRef('settings', $settings);
   parent::display( $tpl );
}


}