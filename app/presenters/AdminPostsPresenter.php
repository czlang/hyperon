<?php

/**
 * 
 *
 * @copyright  Copyright (c) 2011 
 * @package    
 */



/**
 * Admin posts presenter.
 *
 * @author     
 * @package    
 */
final class AdminPostsPresenter extends AdminPresenter
{



	protected function startup()
	{
		parent::startup();
	}
	

	

	public function renderDefault()
	{		
		$posts = new Posts;
		$this->template->posts = $posts->findAll()->orderBy('post_date DESC');	

	}		
	


    public function renderNewPost($data)
	{
		//NDebug::dump($data);
/*
if(isset($draft)){
	$posts = new Posts();
	$save_draft = $posts->insert($post);
}
*/
		
		$tags = new Tags();
		$this->template->tags = $tags->findAll()->fetchAll();

	}

	
    
    public function renderEditPost($id = 0)
	{
		$id = $this->getParam('id');
		
		$posts = new Posts();
        $post_info = $posts->find($id)->fetch();
        $this->template->post_info = $post_info;
		//$this->template->title = 'Janhanus.cz - admin - Úprava novinky - ' . $post_info->post_title . '';

		$form = $this['postForm'];
		if (!$form->isSubmitted()) {
			$posts = new Posts;
			$row = $posts->find($id)->fetch();

			if (!$row) {
				throw new BadRequestException('Record not found');
			}
			$form->setDefaults($row);
		}
	}
    
    
    
		
	public function renderDeletePost($id)
	{
		$this->template->title = 'Smazat novinku';
		$post = new Posts;		
		$this->template->post = $post->find($id)->fetch();			
	}



	public function renderArchives()
	{			
		$posts = new Posts();
		$this->template->posts = $posts->findAll()->orderBy('date DESC')->fetchAll();

	}


	
	/**
	 * New post form component factory.
	 * @return mixed
	 */
	protected function createComponentPostForm()
	{			

		$action = array(
	    	'1' => 'Publish now!',
	    	'2' => 'Save draft',
		);		

		$user = NEnvironment::getUser();

		if($user->isLoggedIn()) $user_id = $user->getIdentity()->data['id'];
		else $user_id = 0;
		
		//$users = new Users();
		//$username = $users->findUsernameByUserId($user_id)->fetchSingle();


		$form = new NAppForm;


		$renderer = $form->renderer;
		$renderer->wrappers['group']['container'] = NULL;
		$renderer->wrappers['pair']['container'] = NULL;
		$renderer->wrappers['controls']['container'] = '';
		$renderer->wrappers['control']['container'] = 'p';
		$renderer->wrappers['control']['errors'] = TRUE;


		$form->addGroup()->setOption('container', NHtml::el('div')->id('action'));

			$form->addRadioList('state', '', $action)
				->setValue(1)
				->addRule(NForm::FILLED, 'Vyberte typ obsahu');

		$form->addGroup();
			$form->addText('title', 'Title *')
				->addRule(NForm::FILLED, 'Nezapomeňte titulek.');

			$form->addTextarea('perex', 'Perex');

			$form->addTextarea('body', 'Body *')
				->addRule(NForm::FILLED, 'Nezapomeňte obsah novinky.')
				->getControlPrototype()->class = "editor";

			$form->addText('meta_description', 'Meta description');

			$form->addText('meta_keywords', 'Meta keywords');

			$form->addText('tags', 'Tags');

			$form->addHidden('author_id', '')
				->setValue($user_id);
			
		$form->addGroup()->setOption('container', NHtml::el('div')->id('send'));
			$form->addSubmit('send', 'Uložit')->onClick[] = array($this, 'sendPostClicked');
			//$form->addSubmit('cancel', 'Neukládat')->setValidationScope(NULL)->onClick[] = array($this, 'CancelClicked');
		
		return $form;
	}
	
	


   /**
	* New post form clicked.
	* 
	*/
    public function sendPostClicked(NSubmitButton $button)
    {
    	if ($button->getForm()->getValues()){
    		$id = (int) $this->getParam('id');
			$posts = new Posts;
			if ($id > 0) {
				$posts->update($id, $button->getForm()->getValues());
				$this->flashMessage('The post has been updated.');
				$this->redirect('Homepage:');
			} else {
				$posts->insert($button->getForm()->getValues());
				$this->flashMessage('Posted!.', 'info');
				$this->redirect('Homepage:');
			}
    	}
    }
	
		
	
	
    /**
	 * Post delete form component factory.
	 * @return mixed
	 */
	protected function createComponentDeletePostForm()
	{
		$form = new NAppForm;		
		
		$form->addSubmit('delete', 'Yes delete!')->getControlPrototype()->class('default');
		$form->addSubmit('cancel', 'Cancel');
		$form->onSubmit[] = array($this, 'deletePostFormSubmitted');		

		return $form;
	}
	
	
	
   /**
	* Delete post form clicked.
	* 
	*/
	public function deletePostFormSubmitted(NAppForm $form)
	{
		if ($form['delete']->isSubmittedBy()) {
			$post = new Posts;
			$post->delete($this->getParam('id'));
			$this->flashMessage('Post deleted!');
		}

		$this->redirect('AdminPosts:archives');
	}
	
	


   /**
	* Cancel clicked.
	* 
	*/
    public function CancelClicked(SubmitButton $button)
    {    	
		$this->redirect('AdminPosts:archives');    	
    }
    
		
}
