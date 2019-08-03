<?php
/**
 * @package    Ajax Tag
 * @author     Dmitry Rekun <d.rekuns@gmail.com>
 * @copyright  Copyright (C) 2019 JPathRu. All rights reserved.
 * @license    GNU General Public License version 3 or later; see LICENSE
 */

defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\Session\Session;
use Joomla\CMS\Language\Text;

$app = Factory::getApplication();

if ($app->isClient('site'))
{
	Session::checkToken('get') or die;
}

$editor = $app->input->getCmd('editor', '');

if (!empty($items)) : ?>
	<style>
	.container-popup {
	  font-family: "Helvetica Neue", Helvetica, Arial, sans-serif;
	}
	#searchTag {
	  width: 100%;
	  padding: 12px 15px;
	  border: 1px solid #ccc;
	  margin-bottom: 12px;
	  border-radius: 3px;
	}
	#tagsList {
	  list-style-type: none;
	  padding: 0;
	  margin: 0;
	}
	#tagsList li {
	  display: inline-block;
	  margin-bottom: 5px;
	}
	#tagsList li a {
	  border: 1px solid #b3b3b3;
	  margin-top: -1px;
	  background-color: #f3f3f3;
	  padding: 4px 12px;
	  font-size: 13px;
	  line-height: 18px;
	  text-decoration: none;
	  color: black;
	  display: block;
	  border-radius: 3px;
	}
	</style>
		<div class="container-popup">
			<input type="text" id="searchTag" onkeyup="seachTag()" placeholder="<?php echo Text::_('PLG_AJAX_TAG_SEARCH_TAG'); ?>">
			<ul id="tagsList">
			<?php foreach ($items as $item) : ?>
				<li><a class="select-link btn" href="javascript:void(0)" data-function="jSelectTag" data-title="<?php echo $item->title; ?>" data-uri="<?php echo $item->link; ?>" data-language=""><?php echo $item->title; ?></a></li>
			<?php endforeach; ?>
			</ul>
		</div>
	<script>
	(function() {
		"use strict";
		window.jSelectTag = function (title, link, lang) {
			var hreflang = '', editor, tag;

			editor = '<?php echo $editor; ?>';

			if (lang !== '')
			{
				hreflang = ' hreflang="' + lang + '"';
			}

			tag = '<a' + hreflang + ' href="' + link + '">' + title + '</a>';

			// Use the API, if editor supports it
			if (window.parent.Joomla && window.parent.Joomla.editors && window.parent.Joomla.editors.instances && window.parent.Joomla.editors.instances.hasOwnProperty(editor)) {
				window.parent.Joomla.editors.instances[editor].replaceSelection(tag)
			} else {
				window.parent.jInsertEditorText(tag, editor);
			}

			window.parent.jModalClose();
		};

		document.addEventListener('DOMContentLoaded', function(){
			var elements = document.querySelectorAll('.select-link');

			for(var i = 0, l = elements.length; l>i; i++) {
				elements[i].addEventListener('click', function (event) {
					event.preventDefault();
					var functionName = event.target.getAttribute('data-function');
						// Used in xtd_contacts
						window[functionName](event.target.getAttribute('data-title'), event.target.getAttribute('data-uri'), event.target.getAttribute('data-language'));
				})
			}

			document.getElementById('searchTag').focus();
		});
	})();

	function seachTag() {
		var input, filter, ul, li, a, i, txtValue;
		input = document.getElementById('searchTag');
		filter = input.value.toUpperCase();
		ul = document.getElementById('tagsList');
		li = ul.getElementsByTagName('li');

		// Loop through all list items, and hide those who don't match the search query
		for (i = 0; i < li.length; i++) {
			a = li[i].getElementsByTagName('a')[0];
			txtValue = a.textContent || a.innerText;
			if (txtValue.toUpperCase().indexOf(filter) > -1) {
				li[i].style.display = '';
			} else {
				li[i].style.display = 'none';
			}
		}
	}
	</script>
<?php else : ?>
	<p><?php echo Text::_('PLG_AJAX_TAG_NO_RECORDS_FOUND'); ?></p>
<?php endif; ?>
