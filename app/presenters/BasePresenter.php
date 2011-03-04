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


    public function  beforeRender() {       

		$user = NEnvironment::getUser();

		if($user->isLoggedIn()){
			$this->template->logged_user = $user->getIdentity()->getData();
		}


		$settings = new Settings();
		$settings = $settings->findAll()->fetchPairs('name', 'value');
		$this->template->settings = $settings;


		$posts = new Posts();
		$this->template->posts = $posts->findAll()->orderBy('date DESC')->fetchAll();


		$texy = new Texy();
			$texy->encoding = 'UTF-8';
			
			$texy->headingModule->balancing = TexyHeadingModule::FIXED; // prepina nadpisy v texy do absolutniho rezimu
			$texy->mergeLines = false;			


		$this->template->registerHelper('texy', array($texy, 'process'));
		$this->template->registerHelper('untexy', array($texy, 'toText'));
	}



	public function renderDefault(){



	}



	public function basePath(){
		$httpRequest = NEnvironment::getHttpRequest();
		$basePath = $httpRequest->getUri()->basePath;
		return $basePath; 
	}





}
