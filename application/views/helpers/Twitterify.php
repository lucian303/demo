<?php
/**
 * Zend_View_Helper_Twitterify
 * A class to process tweets and link links from their feeds as well as has tags @ and #
 *
 * @author Lucian Hontau adopted from snipes.com http://www.snipe.net/2009/09/php-twitter-clickable-links/
 * @param A tweet or text with links and/pr twitter hastags @ / #
 */
class Zend_View_Helper_Twitterify extends Zend_View_Helper_Abstract
{

	public $view;

    public function setView(Zend_View_Interface $view)
    {
        $this->view = $view;
    }

	public function twitterify($ret)
	{
		$ret = preg_replace("#(^|[\n ])([\w]+?://[\w]+[^ \"\n\r\t< ]*)#", "\\1<a href=\"\\2\" target=\"_blank\">\\2</a>", $ret);
		$ret = preg_replace("#(^|[\n ])((www|ftp)\.[^ \"\t\n\r< ]*)#", "\\1<a href=\"http://\\2\" target=\"_blank\">\\2</a>", $ret);
		$ret = preg_replace("/@(\w+)/", "<a href=\"http://www.twitter.com/\\1\" target=\"_blank\">@\\1</a>", $ret);
		$ret = preg_replace("/#(\w+)/", "<a href=\"http://search.twitter.com/search?q=\\1\" target=\"_blank\">#\\1</a>", $ret);
		return $ret;
	}

}
