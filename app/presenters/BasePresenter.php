<?php

/**
 * My NApplication
 *
 * @copyright  Copyright (c) 2010 John Doe
 * @package    MyApplication
 */


/**
 * Base class for all application presenters.
 *
 * @author     John Doe
 * @package    MyApplication
 */
 
abstract class BasePresenter extends NPresenter
{
	    
	public $settings;
    

    protected function startup()
    {
        parent::startup();
		$settings = new Settings();
		$settings = $settings->findAll()->fetchPairs('name', 'value');
		$this->settings = $settings;
		$this->template->settings = $settings;
		if( (!$this->settings['template']) OR (!is_dir(APP_DIR . "/templates/" . $this->settings['template'])) ) {		
			$this->settings['template'] = 'default';
			$this->settings['template_loaded'] = FALSE;			
		}
			else{
				$this->settings['template_loaded'] = TRUE;				
			}
    }

    

    /**
	 * Formats layout template file names.
	 * @param  string
	 * @param  string
	 * @return array
	 */
    public function formatTemplateFiles($presenter, $view)
    {
        $appDir = NEnvironment::getVariable('appDir');
        $path = '/' . str_replace(':', 'Module/', $presenter);
        $pathP = substr_replace($path, '/templates/' . $this->settings["template"], strrpos($path, '/'), 0);
        $path = substr_replace($path, '/templates/' . $this->settings["template"], strrpos($path, '/'));
        return array(
                "$appDir$pathP/$view.latte",
                "$appDir$pathP.$view.latte",
                "$appDir$pathP/$view.phtml",
                "$appDir$pathP.$view.phtml",
                "$appDir$path/@global.$view.phtml", // deprecated
        );
    }

    
    
    /**
	 * Formats layout template file names.
	 * @param  string
	 * @param  string
	 * @return array
	 */
    public function formatLayoutTemplateFiles($presenter, $layout)
    {
        $appDir = NEnvironment::getVariable('appDir');
        $path = '/' . str_replace(':', 'Module/', $presenter);
        $pathP = substr_replace($path, '/templates/' . $this->settings["template"], strrpos($path, '/'), 0);
        $list = array(
                "$appDir$pathP/@$layout.latte",
                "$appDir$pathP.@$layout.latte",
                "$appDir$pathP/@$layout.phtml",
                "$appDir$pathP.@$layout.phtml",
        );
        while (($path = substr($path, 0, strrpos($path, '/'))) !== FALSE) {
                $list[] = "$appDir$path/templates/" . $this->settings["template"] . "/@$layout.latte";
                $list[] = "$appDir$path/templates/" . $this->settings["template"] . "/@$layout.phtml";
        }
        return $list;
    }

    
    
    /**
	 * @return ITemplate
	 */
	protected function createTemplate() {
		// texy
		$texy = new fshlTexy();
		$texy->encoding = 'utf-8';
		$texy->allowedTags = Texy::NONE;
		$texy->allowedStyles = Texy::NONE;
		$texy->setOutputMode(Texy::HTML5);
		//$texy->linkModule->forceNoFollow = TRUE;
		$texy->addHandler('block', array($texy, 'blockHandler'));

		$template = parent::createTemplate();

		// helper
		$template->registerHelper('texy', callback($texy, 'process'));

		return $template;
	}



    /**
	 * Common render method.
	 * @return void
	 */
	public function  beforeRender() {

		$user = NEnvironment::getUser();

		if($user->isLoggedIn()){
			$logged_user = $user->getIdentity()->getData();
			$this->template->logged_user = $logged_user;
		}

		$this->template->viewName = $this->view;

		$a = strrpos($this->name, ':');

		if($a === FALSE) {
		    $this->template->moduleName = '';
		    $this->template->presenterName = $this->name;
      	} else {
		    $this->template->moduleName = substr($this->name, 0, $a + 1);
		    $this->template->presenterName = substr($this->name, $a + 1);
      	}

		$tags = new Tags();
		$all_tags = $tags->findAll()->fetchAll();
		$this->template->all_tags = $all_tags;

		$this->template->registerHelper('timeAgoInWords', 'Helpers::timeAgoInWords');
		$this->template->registerHelper('humanizeTime', 'Helpers::humanizeTime');
		$this->template->registerHelper('humanizeTimeDate', 'Helpers::humanizeTimeDate');
	}



	public function renderDefault($exception){



	}




	public function templatePrepareFilters($template) {
		parent::templatePrepareFilters($template);
		
		// texy
		NTemplateFilters::$texy = new Texy();
		NTemplateFilters::$texy->encoding = 'utf-8';
		NTemplateFilters::$texy->allowedTags = Texy::NONE;
		NTemplateFilters::$texy->allowedStyles = Texy::NONE;
		NTemplateFilters::$texy->setOutputMode(Texy::HTML5);
		
		// filter
		$template->registerFilter('NTemplateFilters::texyElements');
	}



	public function getPostTags($post_id)
	{
		$posts_tags = new PostsTags();
		$post_tags = $posts_tags->findAllByPostId($post_id)->fetchAll();

		if(empty($post_tags[0]->tag_url)){
			$post_tags = FALSE;
		};

		return $post_tags;
	}
    
    
    
    public function getCommentsCount($post_id)
	{
		$comments = new Comments();
        $comments_count =  $comments->countByPostId($post_id);

		return $comments_count;
	}



	public function baseUri(){
		$httpRequest = NEnvironment::getHttpRequest();
		$baseUri = "http://" . $httpRequest->getUri()->host . "" . $httpRequest->getUri()->basePath; 			
		return $baseUri;
	}



	public function basePath(){
		$httpRequest = NEnvironment::getHttpRequest();
		$basePath = $httpRequest->getUri()->basePath;
		return $basePath; 
	}





}
