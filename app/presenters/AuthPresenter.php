<?php

/**
 * 
 *
 * @copyright  Copyright (c) 2010
 * @package    
 */



/**
 * Auth presenter.
 *
 * @author     
 * @package    
 */
final class AuthPresenter extends BasePresenter
{


    protected function startup()
	{
		parent::startup();
	}



    public function renderDefault()
	{

	}
	


    
	public function renderLogin()
	{
        


	}
	
	
	
	public function renderLogout()
	{
		//NEnvironment::getUser()->logout(TRUE);
        NEnvironment::getUser()->logout();
		$this->redirect('Auth:login');
	}

	

    public function renderRegister()
	{
		$user = NEnvironment::getUser();
		if ($user->isLoggedIn()) {
			$this->redirect('Users:');
		}
	}




	/**
	 * Login form component factory.
	 * @return mixed
	 */
	protected function createComponentLoginForm()
	{
		$this->template->title = 'Login';
		
		$form = new NAppForm;
		$form->addText('email', 'Email:')
			->addRule(NForm::FILLED, 'Uveďte prosím svůj email.');

		$form->addPassword('password', 'Heslo:')
			->addRule(NForm::FILLED, 'Uveďte prosím heslo.');

		$form->addSubmit('login', 'Přihlásit');

		$form->addProtection('Please submit this form again (security token has expired).');

		$form->onSubmit[] = array($this, 'loginFormSubmitted');
		
		return $form;
	}


	
	public function loginFormSubmitted($form)
	{
		try {
			$user = NEnvironment::getUser();
            
			$email = mb_strtolower($form['email']->getValue(), 'UTF-8');
			$password = mb_strtolower($form['password']->getValue(), 'UTF-8');

			$user->login($email, sha1($email . $password));

			$logged_user = $user->getIdentity()->getData();

			$this->redirect('Users:');
		} catch (AuthenticationException $e) {
			$form->addError($e->getMessage());
		}
	}



	protected function createComponentRegisterForm()
	{
		$form = new NAppForm;

		$form->addText('username', 'Značka (případně jméno): *')
			->addRule(NForm::FILLED, 'Vyberte si prosím jméno.');
			//->addRule('CustomFormValidators::isUsernameUnique', 'Přezdívka je již obsazená, vyberte si prosím jinou.');

		$form->addText('email', 'E-mail: *', 35)
			//->setOption('description', '(Nikomu se nezobrazí, pokud to v nastavení sami nezměníte)')
			->setEmptyValue('@')
			->addCondition(NForm::FILLED)
				->addRule(NForm::EMAIL, 'Nesprávný formát E-mailové adresy');

		$form->addPassword('password', 'Heslo: *', 20)
			->setOption('description', '(Musí mít alespoň 6 znaků)')
			->addRule(NForm::FILLED, 'Vyberte Vaše heslo')
			->addRule(NForm::MIN_LENGTH, 'Heslo je příliš krátké: musí mít alespoň %d znaků', 6);

		$form->addPassword('password2', 'Zopakujte heslo pro kontrolu: *', 20)
			->addConditionOn($form['password'], NForm::VALID)
				->addRule(NForm::FILLED, 'Heslo znovu')
				->addRule(NForm::EQUAL, 'Hesla se neshodují.', $form['password']);

		$form->addProtection('Odešlete prosím forumlář ještě jednou(bezpečnostní ochrana).');

		$form->addSubmit('register', 'Registrovat')->onClick[] = array($this, 'sendRegisterClicked');

		return $form;
	}



	public function sendRegisterClicked(NSubmitButton $button)
    {
    	if ($button->getForm()->getValues()){
			$user = NEnvironment::getUser();
			$button_dump = $button->getForm()->getValues();

			$users = new Users;

			$email = mb_strtolower($button_dump['email'], 'UTF-8');
			$password = mb_strtolower($button_dump['password'], 'UTF-8');

            //$button_dump['password'] = sha1($email . $password);
            //NDebug::dump(sha1($email . $password));

            $users->insert($button_dump);
			$user->login($email, sha1($email . $password));

			$this->flashMessage('Registrace proběhla úspěšně!.', 'info');
            $this->redirect('Users:');

    	}
    }

	
}
