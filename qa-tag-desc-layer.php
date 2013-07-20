<?php

class qa_html_theme_layer extends qa_html_theme_base
{

	function post_tag_item($taghtml, $class)
	{
		require_once QA_INCLUDE_DIR.'qa-util-string.php';
			
		global $plugin_tag_desc_list, $plugin_tag_map;
		
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
			//var_dump($plugin_tag_map);
		}
		
		if (preg_match('/,TAG_DESC,([^,]*),/', $taghtml, $matches)) {
			$taglc=$matches[1];
			$title=@$plugin_tag_map[$taglc]['title'];
			$title=qa_shorten_string_line($title, qa_opt('plugin_tag_desc_max_len'));
			$taghtml=str_replace($matches[0], qa_html($title), $taghtml);
		}
		if (preg_match('/,TAG_ICON,([^,]*),/', $taghtml, $matches)) {
			$taglc=$matches[1];
			$icon=@$plugin_tag_map[$taglc]['icon'];
			$icon=qa_shorten_string_line($icon, qa_opt('plugin_tag_desc_max_len'));
			if (@$plugin_tag_map[$taglc]['icon']!='')
				{
					if (qa_opt('plugin_tag_desc_enable_icon'))
						$size='width="'.qa_opt('plugin_tag_desc_icon_width').'" height="'.qa_opt('plugin_tag_desc_icon_height').'"';
					else $size='';
					$taghtml=str_replace($matches[0], '<img class="qa-tag-img" '.$size.' src="'.$icon.'">' , $taghtml);
				}
			else 
				$taghtml=str_replace($matches[0], '' , $taghtml);
		}		
		qa_html_theme_base::post_tag_item($taghtml, $class);
	}	

}