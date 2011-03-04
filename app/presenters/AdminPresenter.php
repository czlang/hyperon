<?php

/**
 * 
 *
 * @copyright  Copyright (c) 2010
 * @package    
 */



/**
 * Admin presenter.
 *
 * @author     
 * @package    
 */
abstract class AdminPresenter extends BasePresenter
{
	
	protected function startup()
	{
		parent::startup();

		
		$this->setLayout('admin_layout');	

				
        $user = NEnvironment::getUser();
            if (!$user->isLoggedIn()) {
                if ($user->getLogoutReason() === NUser::INACTIVITY) {
                    $this->flashMessage('You have been logged out due to inactivity. Please login again.');
                }
				$backlink = $this->getApplication()->storeRequest();
				$this->redirect('Auth:login', array('backlink' => $backlink));
            }

		$settings = new Settings();
		$settings = $settings->findAll()->fetchPairs('name', 'value');
		$this->template->settings = $settings;
		
	}


    
}
