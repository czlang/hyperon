<?php

/**
 * My NApplication
 *
 * @copyright  Copyright (c) 2010 John Doe
 * @package    MyApplication
 */



/**
 * Homepage presenter.
 *
 * @author     John Doe
 * @package    MyApplication
 */
final class UsersPresenter extends BasePresenter
{


 	protected function startup()
	{
		parent::startup();

		$user = NEnvironment::getUser();

		if($user->isLoggedIn()){
			$logged_user = $user->getIdentity()->getData();
		}
			else{
				$this->redirect('Auth:login');
			}		
	}


	public function renderDefault()
	{
		$user = NEnvironment::getUser();
		$logged_user = $user->getIdentity()->getData();

	}



	public function renderUser($username)
	{
		$user = NEnvironment::getUser();
		$logged_user = $user->getIdentity()->getData();

		$users = new Users();
		$user = $users->findByUsername($username)->fetch();
		$this->template->user = $user;
	}




    public function renderSettings()
	{
        $user = NEnvironment::getUser();
		$logged_user = $user->getIdentity()->getData();

        $users = new Users();
		$user = $users->find($logged_user['id'])->fetch();

        $form = $this['settingsForm'];
            if (!$form->isSubmitted()) {
                $form->setDefaults($user);
                //NDebug::dump($product);
            }
    }




    protected function createComponentSettingsForm()
	{
		$form = new NAppForm;

		$form->addText('username', 'Značka (případně jméno): *')
			->addRule(NForm::FILLED, 'Vyberte si prosím jméno.');
			//->addRule('CustomFormValidators::isUsernameUnique', 'Přezdívka je již obsazená, vyberte si prosím jinou.');
		$form->addTextarea('description', 'Popis: ');

		$form->addProtection('Odešlete prosím formulář ještě jednou(bezpečnostní ochrana).');

		$form->addSubmit('save', 'Uložit nastavení')->onClick[] = array($this, 'sendSaveSettingsClicked');
        $form->addSubmit('cancel', 'Cancel')->setValidationScope(NULL)->onClick[] = array($this, 'CancelClicked');

		return $form;
	}



	public function sendSaveSettingsClicked(NSubmitButton $button)
    {
    	if ($button->getForm()->getValues()){
			$user = NEnvironment::getUser();
            $logged_user = $user->getIdentity()->getData();
			$button_dump = $button->getForm()->getValues();

			$users = new Users;
            $users->update($logged_user['id'], $button_dump);
            $this->flashMessage('Nastavení uloženo.');
            $this->redirect('Users:settings');
    	}
    }

}
