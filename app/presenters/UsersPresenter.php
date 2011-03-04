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

		$products = new Products();
		$this->template->products = $products->findByUserId($logged_user['id']);
	}



	public function renderUser($username)
	{
		$user = NEnvironment::getUser();
		$logged_user = $user->getIdentity()->getData();

		$users = new Users();
		$user = $users->findByUsername($username)->fetch();
		$this->template->user = $user;

		$products = new Products();
		$this->template->products = $products->findByUserId($user->id);
	}




    public function renderAddProduct()
	{
		$user = NEnvironment::getUser();
		$logged_user = $user->getIdentity()->getData();		
	}



    public function renderProducts()
	{
		$user = NEnvironment::getUser();
		$logged_user = $user->getIdentity()->getData();

        $products = new Products();
		$this->template->products = $products->findByUserId($logged_user['id']);
	}



    public function renderEditProduct($id)
	{
		$products = new Products();		

        $user = NEnvironment::getUser();
		$logged_user = $user->getIdentity()->getData();

		$product = $products->findById($id)->fetch();
        
        if($product->user_id != $logged_user['id']){
            echo "ee";
        }
            else{
                $form = $this['insertProduct'];
                if (!$form->isSubmitted()) {
                    $form->setDefaults($product);
                    //NDebug::dump($product);
                }
            }
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




	protected function createComponentInsertProduct()
	{
		$product_id = (int) $this->getParam('id');

		$products = new Products();
		$product = $products->findById($product_id)->fetch();

		$user = NEnvironment::getUser();
		$logged_user = $user->getIdentity()->getData();		

        $visibility = array(
            '1' => 'Viditelný',
            '0' => 'Neviditelný',
        );

		$form = new NAppForm;
		
		$form->addText('title', 'Název: *')
			->addRule(NForm::FILLED, 'Nezapomeňte název.');
				
		$form->addTextarea('desc', 'Popis:')
			->addRule(NForm::FILLED, 'Nezapomeňte popis.');

		$form->addText('price', 'Cena:')
			->addRule(NForm::FILLED, 'Nezapomeňte cenu.');

        $form->addText('pieces', 'Kusů:')
			->addRule(NForm::FILLED, 'Nezapomeňte počet kusů.');

        $form->addRadioList('visible', 'Viditelnost:', $visibility)
            ->addRule(NForm::FILLED, 'Nezapomeňte nastavit viditelnost.')
                ->setDefaultValue(1);

		$form->addFile('picture', 'Hlavní obrázek:')
			->addCondition(NForm::FILLED)
			->addRule(NForm::MIME_TYPE, 'Soubor není obrázek', 'image/*');

		if(isset($product->picture)){
			$form->addImage('actual_picture', $this->basePath() . '/images/products/' . $product->picture);
		}

		$form->addHidden('user_id')->setValue($logged_user['id']); 

		$form->addSubmit('send', 'Odeslat')->onClick[] = array($this, 'sendNewProductClicked');
		$form->addSubmit('cancel', 'Neodesílat')->setValidationScope(NULL)->onClick[] = array($this, 'CancelClicked');
		
		return $form;
	}
	
	

    public function CancelClicked(SubmitButton $button)
    {    	
		$this->redirect('Users:');
    }
	
	

    public function sendNewProductClicked(NSubmitButton $button)
    {
		$button_values = $button->getForm()->getValues();
		$flash_message = 'Hezky pěkně! Ještě to omrkne Velký administrátor a hotovo.';

        $id = (int) $this->getParam('id');
        
        $products = new Products;
        
        if ($id > 0) {            
            $products->update($id, $button->getForm()->getValues());
            $this->flashMessage($flash_message);
        } else {
            $products->insert($button->getForm()->getValues());
            $this->flashMessage($flash_message);            
        }

        $this->redirect('Users:products');
    }




	public function renderDeleteProduct($id)
	{
		$this->template->title = 'Delete product';
		$products = new Products;
		$this->template->product = $products->findById($id)->fetch();			
	}



	protected function createComponentDeleteProduct()
	{
		$form = new NAppForm;
		
		$form->addSubmit('delete', 'Smazat')->getControlPrototype()->class('default');
		$form->addSubmit('cancel', 'Cancel');
		$form->onSubmit[] = array($this, 'deleteProductSubmitted');

		return $form;
	}
	
	

	public function deleteProductSubmitted(NAppForm $form)
	{
		if ($form['delete']->isSubmittedBy()) {
			$products = new Products;
			$products->delete($this->getParam('id'));
			$this->flashMessage('Produkt smazán!');
		}

		$this->redirect('Users:products');
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
