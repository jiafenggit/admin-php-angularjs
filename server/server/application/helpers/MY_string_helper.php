<?php
/**
* 字符串处理类
*/
if ( ! function_exists('html2text'))
{

	function html2text($str)
	{
		$str = preg_replace("/<sty(.*)\\/style>|<scr(.*)\\/script>|<!--(.*)-->/isU","",$str);
		$alltext = "";
		$start = 1;
		for ($i = 0; $i < strlen($str); $i++)
		{
			if($start == 0 && $str[$i] == ">") $start = 1;
			elseif($start == 1)
			{
				if($str[$i] == "<")
				{
					$start = 0;
					$alltext .= " "; 
				}
				elseif(ord($str[$i]) > 31) $alltext .= $str[$i];
			}
		}
		$alltext = str_replace("　", " ", $alltext);
		$alltext = preg_replace("/&([^;&]*)(;|&)/", "", $alltext);
		$alltext = preg_replace("/[ ]+/s", " ", $alltext);
		return $alltext;
	}
}