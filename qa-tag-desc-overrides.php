<?php

function qa_tag_html($tag, $microformats=false)
{
	global $plugin_tag_desc_list;
	
	$taghtml=qa_tag_html_base($tag, $microformats);
	
	require_once QA_INCLUDE_DIR.'qa-util-string.php';
	
	$taglc=qa_strtolower($tag);
	$plugin_tag_desc_list[$taglc]=true;

	//echo '<pre>';
	//var_dump($taghtml);
	//echo $anglepos;
	//echo '</pre>';
	
	$anglepos=strpos($taghtml, '>');
	if ($anglepos!==false)
		$taghtml=substr_replace($taghtml, ' TITLE=",TAG_DESC,'.$taglc.',"', $anglepos, 0);
	$anglepos=strpos($taghtml, '>');
		if ($anglepos!==false)
		$taghtml=substr_replace($taghtml, ' ,TAG_ICON,', $anglepos+1, 0);

	
	
	return $taghtml;
}
