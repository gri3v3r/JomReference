<?php

defined('_JEXEC') or die('Restricted access'); ?>

<form action="index.php" method="post" name="adminForm">
	<table class="adminlist">
	<thead>
	  <tr>
	   <th width="10"><?php echo JText::_( 'ID' ); ?></th>
	   <th width="10">
		 <input type="checkbox"
		 name="toggle"
		 value="" onclick="checkAll(
		 <?php echo count( $this->settings ); ?>);" />
       </th>
       <th align="center">
         <?php echo JText::_('Recommendation Permission'); ?>
       </th>
	   <th align="center">
         <?php echo JText::_('Business Mode'); ?>
       </th>
	   <th align="center">
         <?php echo JText::_('Recommendation Withdrawal'); ?>
       </th>
	   <th align="center">
         <?php echo JText::_('Ratings'); ?>
       </th>
	   <th align="center">
         <?php echo JText::_('User Manageable Recommendations'); ?>
       </th>
	   <th align="center">
         <?php echo JText::_('Recommendation Request'); ?>
       </th>
      </tr>
    </thead>
   <tbody>
<?php
  $k = 0;
  $i = 0;
  foreach( $this->settings as $row )
  {
   $checked = JHTML::_('grid.id', $i, $row->comp_id );
    $permissions = ($row->rec_perm==1?'<img src="images/tick.png" alttext="enabled"/>':'<img src="images/publish_x.png" alttext="disabled"/>'); 
	$business = ($row->busi_mod==1?'<img src="images/tick.png" alttext="enabled"/>':'<img src="images/publish_x.png" alttext="disabled"/>'); 
	$withdrawals = ($row->rec_del==1?'<img src="images/tick.png" alttext="enabled"/>':'<img src="images/publish_x.png" alttext="disabled"/>'); 
	$ratings = ($row->rate==1?'<img src="images/tick.png" alttext="enabled"/>':'<img src="images/publish_x.png" alttext="disabled"/>'); 
	$manageable = ($row->user_man==1?'<img src="images/tick.png" alttext="enabled"/>':'<img src="images/publish_x.png" alttext="disabled"/>'); 
	$requests = ($row->rec_req==1?'<img src="images/tick.png" alttext="enabled"/>':'<img src="images/publish_x.png" alttext="disabled"/>'); 
?>
     <tr class="<?php echo "row$k"; ?>">
      <td><?php echo $row->comp_id; ?></td>
      <td><?php echo $checked; ?></td>
      <td align="center"><?php echo $permissions;?></td>
	  <td align="center"><?php echo $business;?></td>
	  <td align="center"><?php echo $withdrawals;?></td>
	  <td align="center"><?php echo $ratings;?></td>
	  <td align="center"><?php echo $manageable;?></td>
	  <td align="center"><?php echo $requests;?></td>
    </tr>
   
   <?php
    $k = 1 - $k;
    $i++;
   } ?>
   </tbody>
   </table>

  <input type="hidden" name="option"
  value="<?php echo JRequest::getVar( 'option' ); ?>" />
  <input type="hidden" name="task" value="" />
  <input type="hidden" name="boxchecked" value="0" />
  <input type="hidden" name="hidemainmenu" value="0" />
</form>