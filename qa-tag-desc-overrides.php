<?php

function qa_tag_html($tag, $microformats=false)
{
	global $plugin_tag_desc_list;
	
	$taghtml=qa_tag_html_base($tag, $microformats);
	
	require_once QA_INCLUDE_DIR.'qa-util-string.php';
	
	$taglc=qa_strtolower($tag);
	$plugin_tag_desc_list[$taglc]=true;
	
	$anglepos=strpos($taghtml, '>');
	if ($anglepos!==false)
	{	// it doesn't apply to "tags page", it must be added in next versions
		if (qa_request()!='tags')
		{
			$taghtml=substr_replace($taghtml, ',TAG_ICON,'.$taglc.',' , $anglepos+1, 0);
			$taghtml=substr_replace($taghtml, ' TITLE=",TAG_DESC,'.$taglc.',"', $anglepos, 0);
		}
	}
	//var_dump($taghtml);
	return $taghtml;
}
