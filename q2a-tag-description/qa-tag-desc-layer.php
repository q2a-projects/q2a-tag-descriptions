<?php
class qa_html_theme_layer extends qa_html_theme_base
{
	function post_tag_item($taghtml, $class)
	{
		require_once QA_INCLUDE_DIR.'qa-util-string.php';
		global
			$plugin_tag_desc_list, // Already filled in qa-tag-desc-overrides.php  -  All tags used in this page are listed in this array
			$plugin_tag_map;       // here it will be filled with tag's meta descriptions
		if (count(@$plugin_tag_desc_list)) {
			$result=qa_db_query_sub(
				'SELECT tag, title, content FROM ^tagmetas WHERE tag IN ($)',
				array_keys($plugin_tag_desc_list)
			);
			
			$plugin_tag_desc_map=qa_db_read_all_assoc($result);

			$plugin_tag_desc_list=null;
			
			$plugin_tag_map=array();
			foreach ($plugin_tag_desc_map as &$value) {
				if ($value['title']=='title') $plugin_tag_map[$value['tag']]['title'] = $value['content'];
				if ($value['title']=='description') $plugin_tag_map[$value['tag']]['description'] = $value['content'];
				if ($value['title']=='icon') $plugin_tag_map[$value['tag']]['icon'] = $value['content'];
			}
		}

		$html = new DOMDocument();
		$html->loadHTML(mb_convert_encoding($taghtml, 'HTML-ENTITIES', 'UTF-8'));

		foreach($html->getElementsByTagName('a') as $a)
        {
			if (!empty($plugin_tag_map[$a->nodeValue]['title']))
				$a->setAttribute('title', $plugin_tag_map[$a->nodeValue]['title']);
			if (!empty($plugin_tag_map[$a->nodeValue]['icon'])){
				$element = $html->createElement('img');
				$element->setAttribute('src',$plugin_tag_map[$a->nodeValue]['icon']);
				$element->setAttribute('class','qa-tag-img');
				$element->setAttribute('width',qa_opt('plugin_tag_desc_icon_width'));
				$element->setAttribute('height',qa_opt('plugin_tag_desc_icon_height'));
				 $a->insertBefore($element, $a->firstChild);
			}
		}
		
		$taghtml= $html->saveHTML(); 
		qa_html_theme_base::post_tag_item($taghtml, $class);
	}
	function ranking($ranking)
	{
		if ($this->template=='tags')
		{
			global $plugin_tag_desc_list; // Already filled in qa-tag-desc-overrides.php  -  All tags used in this page are listed in this array
			
			if (count(@$plugin_tag_desc_list)) {
				// Get all tag meta in this query
				$result=qa_db_query_sub(
					'SELECT tag, title, content FROM ^tagmetas WHERE tag IN ($)',
					array_keys($plugin_tag_desc_list)
				);
				$plugin_tag_desc_map=qa_db_read_all_assoc($result);
				$plugin_tag_desc_list=null;
				$plugin_tag_map=array();
				foreach ($plugin_tag_desc_map as &$value) {
					if ($value['title']=='title') $plugin_tag_map[$value['tag']]['title'] = $value['content'];
					if ($value['title']=='description') $plugin_tag_map[$value['tag']]['description'] = $value['content'];
					if ($value['title']=='icon') $plugin_tag_map[$value['tag']]['icon'] = $value['content'];
				}
				// add title and icon to each tag
				$html = new DOMDocument();
				foreach ($ranking['items'] as &$item) {
					$html->loadHTML(mb_convert_encoding($item['label'], 'HTML-ENTITIES', 'UTF-8'));
					foreach($html->getElementsByTagName('a') as $a)
					{
						if (!empty($plugin_tag_map[$a->nodeValue]['title']))
							$a->setAttribute('title', $plugin_tag_map[$a->nodeValue]['title']);
						if (!empty($plugin_tag_map[$a->nodeValue]['icon'])){
							$element = $html->createElement('img');
							$element->setAttribute('src',$plugin_tag_map[$a->nodeValue]['icon']);
							$element->setAttribute('class','qa-tag-img');
							$element->setAttribute('width',qa_opt('plugin_tag_desc_icon_width'));
							$element->setAttribute('height',qa_opt('plugin_tag_desc_icon_height'));
							$a->insertBefore($element, $a->firstChild);
						}
					}
					$item['label']= $html->saveHTML(); 				
				}
			}
		}
		qa_html_theme_base::ranking($ranking);
	}
}