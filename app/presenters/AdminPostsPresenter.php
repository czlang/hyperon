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
	


  	public function handleAutosave($txt)
	{		
		$post_id = $this->getParam('id');

		$posts = new Posts();
		$autosave = new Autosave();

		if(!$post_id){
			$post_id = $posts->getMaxId() + 1;
		}

    	$autosave->autosave($post_id, $txt);		
	}

	

	public function renderDefault($exception)
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

		$tags = new Tags();
		$this->template->tags = $tags->findAll()->fetchAll();

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
		$post = new Posts();		
		$this->template->post = $post->find($id)->fetch();			
	}



	public function renderArchives()
	{			
		$posts = new Posts();
		$this->template->posts = $posts->findAll()->where('state = %i', 1)->or('state = %i', 3)->orderBy('date DESC')->fetchAll();

		$beeps = $posts->findAllFrontend()->where('state = %i', 2)->orderBy('date DESC')->fetchAll();		
		$this->template->beeps = $beeps;


	}


	
	/**
	 * New post form component factory.
	 * @return mixed
	 */
	protected function createComponentPostForm()
	{
		$action = array(
	    	'1' => 'Post',
	    	'2' => 'Beep',
	    	'3' => 'Draft',
		);

		$lang = array(
	    	'1' => 'Czech',
	    	'2' => 'English',
		);

		$user = NEnvironment::getUser();
		if($user->isLoggedIn()) $user_id = $user->getIdentity()->data['id'];
		else $user_id = 0;	

		$post_id = (int) $this->getParam('id');
		if($post_id){
			$posts_tags = new PostsTags();
			$post_tags = $posts_tags->findAllByPostId($post_id)->fetchAll();
			$post_tags_string = "";
			foreach ($post_tags as $key=>$value) {
				$post_tags_string .= ($value["tag"]) . ", ";
			}
		}
		else{
			$post_tags = "";
		}

		$form = new NAppForm;

		$renderer = $form->renderer;
		$renderer->wrappers['group']['container'] = NULL;
		$renderer->wrappers['pair']['container'] = NULL;
		$renderer->wrappers['label']['container'] = '';
		$renderer->wrappers['controls']['container'] = '';
		$renderer->wrappers['control']['container'] = 'p';
		$renderer->wrappers['control']['errors'] = TRUE;


		$form->addGroup();
			$form->addText('title', 'Title');
				//->addRule(NForm::FILLED, 'Nezapomeňte titulek.');

			$form->addTextarea('perex', 'Perex');

			$form->addTextarea('body', 'Body *')
				->addRule(NForm::FILLED, 'Dont forget the post body.')
				->getControlPrototype()->class = "editor";

			$form->addText('meta_description', 'Meta description');

			$form->addText('meta_keywords', 'Meta keywords');

			if($post_id){
				$form->addText('tags', 'Tags')->setValue($post_tags_string);
			}
			else{
				$form->addText('tags', 'Tags');
			}

			$form->addHidden('author_id', '')
				->setValue($user_id);

			$form->addHidden('id', '')
				->setValue($post_id);



		$form->addGroup()->setOption('container', NHtml::el('div')->id('panel'));

			$form->addRadioList('state', '', $action)
				->setValue(1)
				->addRule(NForm::FILLED, 'Content type');

			$form->addRadioList('lang', '', $lang)
				->setValue(1)
				->addRule(NForm::FILLED, 'Choose language');

			$form->getElementPrototype()->div = 'blabla';

			$form->addSubmit('send', 'Save')->onClick[] = array($this, 'sendPostClicked');			
		
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
				$this->redirect('AdminPosts:editpost', $id);
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
