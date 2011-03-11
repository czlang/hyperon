<?php

/**
 * 
 *
 * @copyright  Copyright (c) 2011 
 * @package    
 */



/**
 * Admin users presenter.
 *
 * @author     
 * @package    
 */
final class AdminUsersPresenter extends AdminPresenter
{



	protected function startup()
	{
		parent::startup();
	}
	

	

	public function renderDefault()
	{		
		$users = new Users();
		$registered_users = $users->findAll()->orderBy('username DESC')->fetchAll();
		$this->template->registered_users = $registered_users;
	}		
	


	public function renderEditUser($id)
	{
        $user = NEnvironment::getUser();
		$logged_user = $user->getIdentity()->getData();
		if($logged_user['id'] != $id){
			$this->flashMessage('Sorry, you cannot do this.', 'info');
			$this->redirect('AdminUsers:');
		}

		$users = new Users();
		$user = $users->find($id)->fetch();
		$this->template->user = $users;
	}		
 



	protected function createComponentEditUserForm()
	{		
		$user = NEnvironment::getUser();
		$logged_user = $user->getIdentity()->getData();
		
		$users = new Users;
		$db_user = $users->find($logged_user['id'])->fetch();

		$form = new NAppForm;

		$form->getElementPrototype()->id('user-edit-form');		

		$form->addText('realname', 'Real name:')
			->setValue($db_user->realname);

		$form->addText('email', 'E-mail:', 35)
			->setValue($db_user->email)
			->setEmptyValue('@')
			->addCondition(NForm::FILLED)
				->addRule(NForm::EMAIL, 'Incorrect E-mail Address');			
				
		$form->addFile('avatar', 'Foto (avatar):')
			->addCondition(NForm::FILLED)
				->addRule(NForm::MIME_TYPE, 'Uploaded file is not image', 'image/*');
				
		if($db_user->avatar){
			$form->addImage('actual_avatar', $this->basePath() . '/upload/avatars/' . $db_user->avatar);
		}
				
		$form->addTextarea('about', 'About:')->setValue($db_user->about);			

		$form->addHidden('user_id')->setValue($db_user->id);
		$form->addHidden('username')->setValue($db_user->username);
		
		$form->addProtection('Please submit this form again (security token has expired).');
		
		$form->addSubmit('update', 'Save changes')->onClick[] = array($this, 'sendEditUserClicked');		
		$form->addSubmit('cancel', 'Cancel')->setValidationScope(NULL)->onClick[] = array($this, 'CancelClicked');		

		
		return $form;
	}
  



	
    public function sendEditUserClicked(NSubmitButton $button)
    {
    	if ($button->getForm()->getValues()){
    		$button_dump = $button->getForm()->getValues();

    		$user_id = $this->getParam('id');

    		if($button_dump['avatar']->name != ''){
	    		$image = NImage::fromFile($button_dump['avatar']);
				$image->resize(120, NULL);
				$image->crop('50%', '50%', 120, 120);
				$image->sharpen();
				$filename = NString::webalize($button_dump['avatar']->name, '.');
	    		$image->save(WWW_DIR . '/upload/avatars/' . mb_strtolower(NString::webalize($button_dump['realname']), 'UTF-8') . '_' . $filename);
    		}
    		
			$users = new Users();
			$users->update($button_dump['user_id'], $button->getForm()->getValues());
			$this->flashMessage('Saved.');
			$this->redirect('AdminUsers:');
    	}
    }





    public function CancelClicked(SubmitButton $button)
    {    	
    	$button_dump = $button->getForm()->getValues();
    	$username = $this->getParam('username');
		$this->redirect('Users:user', $username);
    }
	

		
}
