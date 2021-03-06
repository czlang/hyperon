<?php

/**
 * 
 *
 * @copyright  Copyright (c) 2011 
 * @package    
 */



/**
 * Admin settings presenter.
 *
 * @author     
 * @package    
 */
final class AdminSettingsPresenter extends AdminPresenter
{



	protected function startup()
	{
		parent::startup();
	}
	


	public function renderDefault($exception)
	{
		$form = $this['editSettingsForm'];		
		if (!$form->isSubmitted()) {

			$settings = new Settings();
			$settings = $settings->findAll()->fetchPairs('name', 'value');

			if (!$settings) {
				throw new BadRequestException('Record not found');
			}			
			$form->setDefaults($settings);
		}

		if(!$this->settings['template_loaded']) {
			$this->flashMessage('Chosen template does not exist, falling back to default');
		}

		$dir = WWW_DIR . "/backup/mysql";
		foreach (NFinder::findFiles('*.*')->in($dir) as $file) {		
			$file_array[] = $file;
		}
		if(isset($file_array)){
			$this->template->mysql_backup_files = $file_array;
		}
		
	}		
	



  	public function renderBackupDbNow()
	{
		$db_config = NEnvironment::getConfig()->database;		

		set_time_limit(0);
		ignore_user_abort(1);

		$path = WWW_DIR . '/backup/mysql/';
		$file = 'mysql_' . date('Y-m-d_H-i') . '.sql.gz';

		$dump = new MySQLDump(new mysqli($db_config['host'], $db_config['username'], $db_config['password'], $db_config['database']));		
		$dump->save($path . $file);

		$this->flashmessage($file . ' created');
		$this->redirect('AdminSettings:default');		
	}



	
	/**
	 * Edit settings form component factory.
	 * @return mixed
	 */
	protected function createComponentEditSettingsForm()
	{
		$form = new NAppForm();

		$form->addText('web_name', 'Blog Name');
		$form->addText('web_desc', 'Blog Description');
		$form->addTextarea('about', 'Short about');
		$form->addText('web_email', 'Email');
		$form->addTextarea('meta_description', 'Meta description');
		// $form->addTextarea('meta_keywords', 'Meta keywords');
		$form->addText('google_analytics_id', 'Google analytics id');
		$form->addCheckbox('socials', 'Socials');
		$form->addCheckbox('tw_social', 'Twitter share button');
		$form->addCheckbox('fb_social', 'Facebook like button');
		$form->addCheckbox('gplus_social', 'Google + share button');
		$form->addText('template', 'Template');
		$form->addCheckbox('comments_enabled', 'Comments globally enabled');
		
		$form->addSubmit('send', 'Save')->onClick[] = array($this, 'sendEditSettingsClicked');
		$form->addSubmit('cancel', 'Cancel')->setValidationScope(NULL)->onClick[] = array($this, 'CancelClicked');

		return $form;
	}
	
	


   /**
	* Edit settings form clicked.
	* 
	*/
    public function sendEditSettingsClicked(NSubmitButton $button)
    {
    	if ($button->getForm()->getValues()){
			$settings = new Settings();
			$settings->update($button->getForm()->getValues());
			$this->flashMessage('Settings updated.');
			$this->redirect('AdminSettings:');
    	}
    }
	
		
	


   /**
	* Cancel clicked.
	* 
	*/
    public function CancelClicked(NSubmitButton $button)
    {    	
		$this->redirect('AdminSettings:');    	
    }
    
		
}
