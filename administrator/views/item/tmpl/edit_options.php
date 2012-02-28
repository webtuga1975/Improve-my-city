<?php
/**
 * @version		$Id: edit_options.php 21746 2011-07-06 09:07:36Z chdemko $
 * @package		Joomla.Administrator
 * @subpackage	com_categories
 * @copyright	Copyright (C) 2005 - 2011 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

// No direct access.
defined('_JEXEC') or die; ?>

<?php echo JHtml::_('sliders.panel',JText::_('JGLOBAL_FIELDSET_PUBLISHING'), 'publishing-details'); ?>

	<fieldset class="panelform">
		<ul class="adminformlist">

			<li><?php echo $this->form->getLabel('created_user_id'); ?>
			<?php echo $this->form->getInput('created_user_id'); ?></li>




		</ul>
	</fieldset>

<?php $fieldSets = $this->form->getFieldsets('params');

foreach ($fieldSets as $name => $fieldSet) :
	$label = !empty($fieldSet->label) ? $fieldSet->label : 'COM_IMPROVEMYCITY_'.$name.'_FIELDSET_LABEL';
	echo JHtml::_('sliders.panel',JText::_($label), $name.'-options');
	if (isset($fieldSet->description) && trim($fieldSet->description)) :
		echo '<p class="tip">'.$this->escape(JText::_($fieldSet->description)).'</p>';
	endif;
	?>
	<fieldset class="panelform">
	<ul class="adminformlist">

		<?php foreach ($this->form->getFieldset($name) as $field) : ?>
			<li><?php echo $field->label; ?>
			<?php echo $field->input; ?></li>
		<?php endforeach; ?>

		<?php if ($name=='basic'):?>
			<li><?php echo $this->form->getLabel('note'); ?>
			<?php echo $this->form->getInput('note'); ?></li>
		<?php endif;?>
	</ul>

	</fieldset>
<?php endforeach; ?>
