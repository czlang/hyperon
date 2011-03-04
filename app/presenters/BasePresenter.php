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



	protected function createTemplate() {
		// texy
		$texy = new fshlTexy();
		$texy->encoding = 'utf-8';
		$texy->allowedTags = Texy::NONE;
		$texy->allowedStyles = Texy::NONE;
		$texy->setOutputMode(Texy::HTML5);
		$texy->addHandler('block', array($texy, 'blockHandler'));

		$template = parent::createTemplate();

		// helper
		$template->registerHelper('texy', callback($texy, 'process'));

		return $template;
	}





    public function  beforeRender() {       

		$user = NEnvironment::getUser();

		if($user->isLoggedIn()){
			$this->template->logged_user = $user->getIdentity()->getData();
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




		$settings = new Settings();
		$settings = $settings->findAll()->fetchPairs('name', 'value');
		$this->template->settings = $settings;


		$posts = new Posts();
		$this->template->posts = $posts->findAll()->orderBy('date DESC')->fetchAll();

/*
		$texy = new Texy();
			$texy->encoding = 'UTF-8';
			
			$texy->headingModule->balancing = TexyHeadingModule::FIXED; // prepina nadpisy v texy do absolutniho rezimu
			$texy->mergeLines = false;			


		$this->template->registerHelper('texy', array($texy, 'process'));
		$this->template->registerHelper('untexy', array($texy, 'toText'));
*/
	}



	public function renderDefault(){



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




	public function basePath(){
		$httpRequest = NEnvironment::getHttpRequest();
		$basePath = $httpRequest->getUri()->basePath;
		return $basePath; 
	}





}
