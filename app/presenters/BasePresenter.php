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
    private $mobile_template_folder;

    protected function startup() {
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

		$this->mobile_template_folder = "";
		if(Functions::isBrowserMobile()){
			$this->mobile_template_folder = "/mobile";
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
        $pathP = substr_replace($path, '/templates/' . $this->settings["template"].$this->mobile_template_folder, strrpos($path, '/'), 0);
        $path = substr_replace($path, '/templates/' . $this->settings["template"].$this->mobile_template_folder, strrpos($path, '/'));
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
                $list[] = "$appDir$path/templates/" . $this->settings["template"].$this->mobile_template_folder."/@$layout.latte";
                $list[] = "$appDir$path/templates/" . $this->settings["template"].$this->mobile_template_folder."/@$layout.phtml";
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
		$texy->headingModule->balancing = TexyHeadingModule::FIXED;
		$texy->headingModule->generateID = TRUE;
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

		$this->template->registerHelper('timeAgoInWords', 'Helpers::timeAgoInWords');
		$this->template->registerHelper('humanizeTime', 'Helpers::humanizeTime');
		$this->template->registerHelper('humanizeTimeDate', 'Helpers::humanizeTimeDate');
		$this->template->registerHelper('czechDay', 'Helpers::czechDay');
		$this->template->registerHelper('czechMonth', 'Helpers::czechMonth');
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




	/**
	 * Comment form component factory.
	 * @return mixed
	 */
	protected function createComponentSearchForm()
	{
		$s_query = $this->getParam('s_query');
		if($this->getParam('lang')){$search = "Search";}
		else{$search = "Hledat";}
		
		$form = new NAppForm;

		$renderer = $form->getRenderer();
		$renderer->wrappers['controls']['container'] = '';
		$renderer->wrappers['control']['container'] = '';
		$renderer->wrappers['pair']['container'] = '';
		$renderer->wrappers['label']['container'] = '';
		
		$form->setMethod('get');
		
		$renderer = $form->getRenderer();
		$renderer->wrappers['controls']['container'] = '';		
		$renderer->wrappers['control']['container'] = '';
		$renderer->wrappers['pair']['container'] = '';
		$renderer->wrappers['label']['container'] = '';
		
		$form->addText('s_query', '')
			->addRule(NForm::FILLED, '?')
			->setValue($s_query);
		
		$form->addSubmit('search', $search)->onClick[] = array($this, 'sendSearchFormClicked');
		
		return $form;
	}
	
	
	
    public function sendSearchFormClicked(NSubmitButton $button)
    {
    	if ($button->getForm()->getValues()){
 			$dump = $button->getForm()->getValues();
			$this->redirect('Search:result', $dump['s_query']);
    	}
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
    
    
    
    public function getCommentsCount($post_id) {
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


	public function actionDownload($file) {
	        $this->sendResponse( new NDownloadResponse($file) );
	}



	public function getAllTags() {
		$tags = new Tags();
		$all_tags = $tags->findAll()->fetchAll();
		return $all_tags;
	}


	public function processPostTags($tags) {
		$tags_array = explode(",", $tags);
		$tags_final = array();
		foreach ($tags_array as $key => $value) {
			$sub_tag = explode("-", $value);
			if(isset($sub_tag[1])){
				$tags_final[$sub_tag[0]] = $sub_tag[1];
			}
		}
		return $tags_final;
	}

	


}
