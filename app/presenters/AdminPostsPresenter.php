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
	


    public function renderNewPost()
	{
		
	}

	
    
    public function renderEditPost($id = 0)
	{
		$id = $this->getParam('id');
		
		$posts = new Posts();
        $post_info = $posts->find($id)->fetch();
        $this->template->post_info = $post_info;
		$this->template->title = 'Janhanus.cz - admin - Úprava novinky - ' . $post_info->post_title . '';

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



	
	/**
	 * New post form component factory.
	 * @return mixed
	 */
	protected function createComponentPostForm()
	{			
		$form = new NAppForm;		
		
		$form->addText('title', 'Titulek: *')
			->addRule(NForm::FILLED, 'Nezapomeňte titulek.');
		$form->addTextarea('body', 'Obsah novinky: *')				
			->addRule(NForm::FILLED, 'Nezapomeňte obsah novinky.')
			->getControlPrototype()->class = "editor";				
			
		$form->addSubmit('send', 'Uložit novinku')->onClick[] = array($this, 'sendPostClicked');
		$form->addSubmit('cancel', 'Neukládat')->setValidationScope(NULL)->onClick[] = array($this, 'CancelClicked');
		
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
		$form = new AppForm;		
		
		$form->addSubmit('delete', 'Ano smazat!')->getControlPrototype()->class('default');
		$form->addSubmit('cancel', 'Cancel');
		$form->onSubmit[] = array($this, 'deletePostFormSubmitted');		

		return $form;
	}
	
	
	
   /**
	* Delete post form clicked.
	* 
	*/
	public function deletePostFormSubmitted(AppForm $form)
	{
		if ($form['delete']->isSubmittedBy()) {
			$post = new Posts;
			$post->delete($this->getParam('id'));
			$this->flashMessage('Post deleted!');
		}

		$this->redirect('AdminPosts:');
	}
	
	


   /**
	* Cancel clicked.
	* 
	*/
    public function CancelClicked(SubmitButton $button)
    {    	
		$this->redirect('AdminPosts:');    	
    }
    
		
}
