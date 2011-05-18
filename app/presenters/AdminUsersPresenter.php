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
	

	

	public function renderDefault($exception)
	{		
		$users = new Users();
		$registered_users = $users->findAll()->orderBy('username DESC')->fetchAll();
		$this->template->registered_users = $registered_users;		
	}		
	


	public function renderRegister()
	{
        
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



	public function renderAddUser()
	{
        
	}
 


	protected function createComponentUserForm()
	{	
		//NDebug::dump($this->params["action"]);

		if($this->params["action"] == "edituser"){
			$user = NEnvironment::getUser();
			$logged_user = $user->getIdentity()->getData();
			
			$users = new Users;
			$db_user = $users->find($logged_user['id'])->fetch();
		}

		$form = new NAppForm;

		$form->getElementPrototype()->id('user-edit-form');		

		if($this->params["action"] == "edituser"){
			$realname = $db_user->realname;
			$avatar = $db_user->avatar;
			$about = $db_user->about;
			$id = $db_user->id;
			$username = $db_user->username;
			$email = $db_user->email;
		}
			else{
				$realname = "";
				$avatar = "";
				$about = "";
				$id = "";
				$username = "";
				$email = "";
			}		

		$form->addText('realname', 'Real name:')->setValue($realname);

		if($this->params["action"] == "adduser"){
			$form->addPassword('password', 'Your new password: *', 20)
				->setOption('description', '(At least 6 characters please.)')
				->addRule(NForm::FILLED, 'Your new password please')
				->addRule(NForm::MIN_LENGTH, 'Password is too weak: must be at least %d characters long', 6);
					
			$form->addPassword('password2', 'Your new password again: *', 20)
				->addConditionOn($form['password'], NForm::VALID)
					->addRule(NForm::FILLED, 'Heslo znovu')
					->addRule(NForm::EQUAL, 'Hesla se neshodují.', $form['password']);
		}

		$form->addText('email', 'E-mail: *', 35)
			->setValue($email)
			->setEmptyValue('@')
			->addCondition(NForm::FILLED)
				->addRule(NForm::EMAIL, 'Incorrect E-mail Address');			
				
		$form->addFile('avatar', 'Foto (avatar):')
			->addCondition(NForm::FILLED)
				->addRule(NForm::MIME_TYPE, 'Uploaded file is not image', 'image/*');
				
		if($avatar){
			$form->addImage('actual_avatar', $this->basePath() . '/upload/avatars/' . $avatar);
		}
				
		$form->addTextarea('about', 'About:')->setValue($about);

		$form->addHidden('user_id')->setValue($id);
		$form->addHidden('username')->setValue($username);
		
		$form->addProtection('Please submit this form again (security token has expired).');
		
		$form->addSubmit('update', 'Save changes')->onClick[] = array($this, 'sendUserClicked');		
		$form->addSubmit('cancel', 'Cancel')->setValidationScope(NULL)->onClick[] = array($this, 'CancelClicked');		

		
		return $form;
	}
  


	protected function createComponentInviteUserForm()
	{
		$form = new NAppForm;

		$renderer = $form->renderer;
		$renderer->wrappers['group']['container'] = NULL;
		$renderer->wrappers['pair']['container'] = NULL;
		$renderer->wrappers['label']['container'] = '';
		$renderer->wrappers['controls']['container'] = '';
		$renderer->wrappers['control']['container'] = '';
		$renderer->wrappers['control']['errors'] = TRUE;

		$form->addText('recipient', 'E-mail: *', 35)
			->setEmptyValue('@')
			->addCondition(NForm::FILLED)
				->addRule(NForm::EMAIL, 'Incorrect E-mail Address');

		$form->addProtection('Please submit this form again (security token has expired).');
		
		$form->addSubmit('invite', 'Send invitation')->onClick[] = array($this, 'sendInviteUserClicked');		
		
		return $form;
	}


	
    public function sendUserClicked(NSubmitButton $button)
    {		
    	if ($button->getForm()->getValues()){
    		$button_dump = $button->getForm()->getValues();

    		if($button_dump['avatar']->name != ''){
	    		$image = NImage::fromFile($button_dump['avatar']);
				$image->resize(120, NULL);
				$image->crop('50%', '50%', 120, 120);
				$image->sharpen();
				$filename = NString::webalize($button_dump['avatar']->name, '.');
	    		$image->save(WWW_DIR . '/upload/avatars/' . mb_strtolower(NString::webalize($button_dump['realname']), 'UTF-8') . '_' . $filename);
    		}

			if($button_dump["user_id"] == ""){
		    	$users = new Users();
				$users->insert($button->getForm()->getValues());
			}
				else{		    		
					$users = new Users();
					$users->update($button_dump['user_id'], $button->getForm()->getValues());
				}

			$this->flashMessage('Saved.');
			$this->redirect('AdminUsers:');			
    	}
		
    }




    public function sendInviteUserClicked(NSubmitButton $button)
    {		
    	if ($button->getForm()->getValues()){
    		$button_dump = $button->getForm()->getValues();

			$settings = new Settings();
			$settings = $settings->findAll()->fetchPairs('name', 'value');			

			$users = new Users();
			$secure_link = sha1(time() . $button_dump["recipient"]);
			$users->insertInvited($secure_link, $button_dump['recipient']);
			$invited_user = $users->getInvited($button_dump['recipient'])->fetch();			

			//NDebug::dump($secure_link);

			$mail = new NMail;
			$mail->setFrom($settings['web_email'])->addTo($button_dump['recipient']);
	        $mail->setSubject('invitation from ' . $settings['web_name']);
	        $mail->setBody(
				'Bloggers from ' .  $settings['web_name'] . ' wish you to contribute to their blog on ' . $this->baseUri() . ' You can register here ' . $this->baseUri() . 'register?hash=' . $secure_link . '.' . ''
			);

			//NDebug::dump($mail);
			
	        $mail->send();

			$this->flashMessage('Invited.');
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
