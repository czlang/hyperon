<?php

/**
 * 
 *
 * @copyright  Copyright (c) 2011
 * @package    
 */



/**
 * Feed channel presenter.
 *
 * @author
 * @package    
 */
final class FeedPresenter extends BasePresenter
{


	public function beforeRender()
	{
		$this->setLayout(FALSE);
	}



	public function renderDefault($exception) {

		$texy = new Texy();
			$texy->encoding = 'UTF-8';
			$texy->headingModule->balancing = TexyHeadingModule::FIXED;
		

		$settings = new Settings();
		$settings = $settings->findAll()->fetchPairs('name', 'value');		

        /* @var RssControl */
        $rss = $this["rss"];

        // properties
   		$rss->title = $settings['web_name'] . "  - RSS odběr";
        $rss->description = $texy->process($settings['web_desc']);

		$rss->link = $this->baseUri();
        // $rss->setChannelProperty("lastBuildDate", time());
        
        // items
        $posts = new Posts();
		$items = $posts->findAllForRSS()
					->where('posts.state = %i', 1)
					//->or('posts.state = %i', 2)	//wanna beeps in rss ?
					->orderBy('id DESC')
					->limit('0, 10')
					->fetchAll();
		//ndebug::dump($items);
        foreach ($items as $item) {
        	$item["link"] = $this->link("//Posts:post", $item["link"]);
			$item["description"] = $texy->process($item["description"]);
			$item["description"] = $texy->process($item["perex"].$item["description"]);
            unset($item["id"]);
            unset($item["perex"]);
        }		
        $rss->items = $items;
    }

    
   /**
	* Creating Rss component
	* 
	*/
    protected function createComponentRss() {
        return new RssControl;
    }
    
    
}
