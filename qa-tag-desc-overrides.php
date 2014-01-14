<?php
// here we will list all tags used in a list/question to use later in a query
function qa_tag_html($tag, $microformats=false, $favorited=false)
{
	global $plugin_tag_desc_list;
	
	$taghtml=qa_tag_html_base($tag, $microformats, $favorited);
	
	require_once QA_INCLUDE_DIR.'qa-util-string.php';
	
	$taglc=qa_strtolower($tag);
	$plugin_tag_desc_list[$taglc]=true;

	return $taghtml;

}
