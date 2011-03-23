<?php

/**
 * My NApplication
 *
 * @copyright  Copyright (c) 2010 John Doe
 * @package    MyApplication
 */



/**
 * Register presenter.
 *
 * @author     John Doe
 * @package    MyApplication
 */
final class RegisterPresenter extends BasePresenter
{

	public function renderDefault()
	{        
		//$hash = $this->getParam('hash');
		//NDebug::dump($hash);
	}




	protected function createComponentRegisterForm()
	{
		$hash = $this->getParam('hash');
			
		$users = new Users;
		$db_user = $users->getInvitedByHash($hash)->fetch();

		$form = new NAppForm;

		$form->getElementPrototype()->id('register-form');

		$form->addText('realname', 'Real name:');
		
		$form->addPassword('password', 'Your new password: *', 20)
			->setOption('description', '(At least 6 characters please.)')
			->addRule(NForm::FILLED, 'Your new password please')
			->addRule(NForm::MIN_LENGTH, 'Password is too weak: must be at least %d characters long', 6);
					
		$form->addPassword('password2', 'Your new password again: *', 20)
			->addConditionOn($form['password'], NForm::VALID)
				->addRule(NForm::FILLED, 'Heslo znovu')
				->addRule(NForm::EQUAL, 'Hesla se neshodují.', $form['password']);

		$form->addText('email', 'E-mail: *', 35)
			->setValue($db_user["email"])
			->setEmptyValue('@')
			->addCondition(NForm::FILLED)
				->addRule(NForm::EMAIL, 'Incorrect E-mail Address');			
				
		$form->addFile('avatar', 'Foto (avatar):')
			->addCondition(NForm::FILLED)
				->addRule(NForm::MIME_TYPE, 'Uploaded file is not image', 'image/*');
				
		$form->addTextarea('about', 'About:');
		
		$form->addProtection('Please submit this form again (security token has expired).');
		
		$form->addSubmit('update', 'Save changes')->onClick[] = array($this, 'sendRegisterFormClicked');		
		$form->addSubmit('cancel', 'Cancel')->setValidationScope(NULL)->onClick[] = array($this, 'CancelClicked');		

		
		return $form;
	}
  



 	public function sendRegisterFormClicked(NSubmitButton $button)
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
			
		    $users = new Users();
			$users->register($button->getForm()->getValues());				

			$this->flashMessage('Saved.');
			$this->redirect('AdminUsers:');			
    	}
		
    }



}
