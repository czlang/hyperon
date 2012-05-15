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
		$this->setLayout('login');
	}



    public function renderDefault($exception)
	{

	}
	


    
	public function renderLogin()
	{
		$this->template->title = 'Login';
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

			$this->redirect('AdminPosts:newpost');
		} catch (NAuthenticationException $e) {
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




 
    public function twitterLoginLinkConstruct()
    {
        // Twitter login link construction
        $config = NConfig::fromFile(APP_DIR . '/config.ini')->common->const;

        $twitter_oauth = new TwitterOAuth($config['TW_CONSUMER_KEY'], $config['TW_CONSUMER_SECRET']);

        $twitter_request_token = $twitter_oauth->getRequestToken();

        $namespace = NEnvironment::getSession('twitter_auth');

        $namespace->request_token_key = $twitter_request_token['oauth_token'];
        $namespace->request_token_secret = $twitter_request_token['oauth_token_secret'];

        $this->template->twitter_login_url = $twitter_oauth->getAuthorizeURL($twitter_request_token['oauth_token']);
    }




    public function facebookLogin()
    {

        $config = NConfig::fromFile(APP_DIR . '/config.ini')->common->const;

        $facebook = new Facebook(array(
            'appId'  => $config['FB_APPID'],
            'secret' => $config['FB_SECRET'],
            'cookie' => true,
        ));

        $this->template->facebook_login_url = $facebook->getLoginUrl();

        $from_facebook = $this->getParam('session');
        
        if(isset($from_facebook)){
            $session = $facebook->getSession();

            if ($session) {
                $uid = $facebook->getUser();
                $me = $facebook->api('/me');
                $username = $me['name'];
                $password = sha1($me['name'] . $session['access_token']);
                $session['name'] = $me['name'];

                $users = new Users();
                $existing_facebook_user = $users->findFacebookUser($session['uid'])->fetchAll();

                $user = Environment::getUser();

                if($existing_facebook_user){
                    $users->updateFacebookUser($session['uid'], $password, $session['sig'], $session['access_token']);
                    $user->login($username, $password);
                    $logged_user = $user->getIdentity()->getData();
                    $this->redirect('Users:user', $username);
                }
                    else{
                        $ch = curl_init('https://graph.facebook.com/' . $session['uid'] . '/picture?type=large');
						curl_setopt($ch, CURLOPT_HEADER, 1);
						curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
						$header_string = curl_exec($ch);
						$pieces = explode(" ", $header_string);
						$location = trim(str_replace("P3P:", "", $pieces["14"]));
						curl_close($ch);

						$ch2 = curl_init($location);
 						$filename = String::webalize($me['name']) . '.' . rand(0, 1000) . '.jpg';
						$fp = fopen(WWW_DIR . '/upload/avatars/' . $filename, 'wb');
						curl_setopt($ch2, CURLOPT_FILE, $fp);
						curl_exec($ch2);
						curl_close($ch2);
						fclose($fp);

						$session['avatar'] = $filename;

                        $users->insertFacebookUser($session);
                        $user->login($username, $password);
                        $this->flashMessage('Registrace proběhla úspěšně! Tohle je Váš zbrusu nový profil na Muzikopedii.', 'info');
                        $this->redirect('Users:user', $username);
                    }
                }

            }
    }


	
}
