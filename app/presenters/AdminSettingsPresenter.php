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
		$form->addText('web_email', 'Email');
		$form->addTextarea('meta_description', 'Meta description');
		$form->addTextarea('meta_keywords', 'Meta keywords');		
		$form->addText('template', 'Template');		
		
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
