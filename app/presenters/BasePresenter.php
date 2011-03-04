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



    public function basketCount(){
		$session = NEnvironment::getSession();
        $basket = new Basket();
        
        $basket_count = $basket->countBySessid($session->getId());
		return $basket_count;
	}




}
