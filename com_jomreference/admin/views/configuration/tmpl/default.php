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
defined('_JEXEC') or die('Restricted access'); ?>


<form action="index.php" method="post"
name="adminForm" id="adminForm">
<div class="col100">
<fieldset class="adminform">
<legend><?php echo JText::_( 'Details' ); ?></legend>
<table class="admintable">

<tr>
<td width="100" align="right" class="key">
<label for="rec_perm">
<?php echo JText::_( 'Recommendation Permission' ); ?>:
</label>
</td>
<td><?php echo JHTML::_( 'select.booleanlist',
'rec_perm',
'class="inputbox"',
$this->setting->rec_perm ); ?>
</td>
</tr>

<tr>
<td width="100" align="right" class="key">
<label for="busi_mod">
<?php echo JText::_( 'Business Mode' ); ?>:
</label>
</td>
<td><?php echo JHTML::_( 'select.booleanlist',
'busi_mod',
'class="inputbox"',
$this->setting->busi_mod ); ?>
</td>
</tr>

<tr>
<td width="100" align="right" class="key">
<label for="rec_del">
<?php echo JText::_( 'Recommendation Withdrawal' ); ?>:
</label>
</td>
<td><?php echo JHTML::_( 'select.booleanlist',
'rec_del',
'class="inputbox"',
$this->setting->rec_del ); ?>
</td>
</tr>

<tr>
<td width="100" align="right" class="key">
<label for="rate">
<?php echo JText::_( 'Ratings' ); ?>:
</label>
</td>
<td><?php echo JHTML::_( 'select.booleanlist',
'rate',
'class="inputbox"',
$this->setting->rate ); ?>
</td>
</tr>


<tr>
<td width="100" align="right" class="key">
<label for="user_man">
<?php echo JText::_( 'User Manageable Recommendations' ); ?>:
</label>
</td>
<td><?php echo JHTML::_( 'select.booleanlist',
'user_man',
'class="inputbox"',
$this->setting->user_man ); ?>
</td>
</tr>

<tr>
<td width="100" align="right" class="key">
<label for="rec_req">
<?php echo JText::_( 'rec_req' ); ?>:
</label>
</td>
<td><?php echo JHTML::_( 'select.booleanlist',
'rec_req',
'class="inputbox"',
$this->setting->rec_req ); ?>
</td>
</tr>

</table>
</fieldset>
</div>
<div class="clr"></div>
<input type="hidden" name="option"
value="<?php echo JRequest::getVar( 'option' ); ?>" />
<input type="hidden" name="comp_id"
value="<?php echo $this->setting->comp_id; ?>" />
<input type="hidden" name="task" value="" />
</form>