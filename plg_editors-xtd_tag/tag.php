<?php
/**
 * @package    Editor Button - Tag
 * @author     Dmitry Rekun <d.rekuns@gmail.com>
 * @copyright  Copyright (C) 2019 JPathRu. All rights reserved.
 * @license    GNU General Public License version 3 or later; see LICENSE
 */

defined('_JEXEC') or die;

use Joomla\CMS\Language\Text;

/**
 * Editor Tag buton
 *
 * @since  1.0
 */
class PlgButtonTag extends JPlugin
{
	/**
	 * Load the language file on instantiation.
	 *
	 * @var    boolean
	 * @since  1.0
	 */
	protected $autoloadLanguage = true;

	/**
	 * Display the button
	 *
	 * @param   string  $name  The name of the button to add
	 *
	 * @return  JObject  The button options as JObject
	 *
	 * @since   1.0
	 */
	public function onDisplay($name)
	{
		$link = 'index.php?option=com_ajax&amp;plugin=tag&format=raw&'
			. JSession::getFormToken() . '=1&amp;editor=' . $name;

		$button = new JObject;
		$button->modal   = true;
		$button->class   = 'btn addTag';
		$button->link    = $link;
		$button->text    = Text::_('PLG_EDITORS-XTD_TAG_BUTTON_TAG');
		$button->name    = 'file-add';
		$button->options = "{handler: 'iframe', size: {x: 800, y: 500}}";

		return $button;
	}
}
