<?php
/**
 * @package    Ajax Tag
 * @author     Dmitry Rekun <d.rekuns@gmail.com>
 * @copyright  Copyright (C) 2019 JPathRu. All rights reserved.
 * @license    GNU General Public License version 3 or later; see LICENSE
 */

defined('_JEXEC') or die;

use Joomla\CMS\MVC\Model\BaseDatabaseModel;
use Joomla\CMS\Plugin\PluginHelper;

/**
 * Base plugin class
 *
 * @since  1.0
 */
class PlgAjaxTag extends JPlugin
{
	/**
	 * Load the language file on instantiation.
	 *
	 * @var    boolean
	 * @since  1.0
	 */
	protected $autoloadLanguage = true;

	/**
	 * Gets the content for modal window
	 *
	 * @return  string  The content for modal window
	 *
	 * @since   1.0
	 */
	public function onAjaxTag()
	{
		$items = $this->getTags();

		ob_start();
		require_once PluginHelper::getLayoutPath($this->_type, $this->_name, 'default');
		$contents = ob_get_contents();
		ob_get_clean();

		return $contents;
	}

	/**
	 * Gets the list of tags
	 *
	 * @return  array|null
	 *
	 * @since   1.0
	 */
	private function getTags()
	{
		BaseDatabaseModel::addIncludePath(JPATH_ADMINISTRATOR . '/components/com_tags/models', 'TagsModel');

		$model = BaseDatabaseModel::getInstance('Tags', 'TagsModel', array('ignore_request' => true));

		$model->setState('filter.published', 1);
		$model->setState('list.ordering', 'title');
		$model->setState('list.direction', 'ASC');

		$items = $model->getItems();

		if (!empty ($items))
		{
			JLoader::register('TagsHelperRoute', JPATH_SITE . '/components/com_tags/helpers/route.php');

			foreach ($items as $item)
			{
				$item->link = TagsHelperRoute::getTagRoute($item->id);

				$search  = 'id=' . $item->id;
				$replace = $search . ':' . $item->alias;
				$item->link = str_replace($search, $replace, $item->link);
			}
		}

		return $items;
	}
}
