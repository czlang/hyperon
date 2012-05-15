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
    
    
    
   	public function handleDeleteComment($id)
	{
		$comments = new Comments();
        $comments->delete($id);
        $this->redirect('AdminPosts:archives');
	}



   	public function handleDeleteTag($id)
	{
		$tags = new Tags();
		$post_tags = new PostsTags();

		$post_tags->delete($id);
        $tags->delete($id);
		
        $this->redirect('AdminPosts:archives');
	}

	

	public function renderDefault($exception)
	{		
		$posts = new Posts;
		$this->template->posts = $posts->findAll()->orderBy('post_date DESC');

	}		
	

    
    public function renderArchives()
	{			
		$posts = new Posts();
		$comments = new Comments();
		$tags = new Tags();

//		$beeps = $posts->findAll()->where('state = %i', 2)->orderBy('date DESC')->fetchAll();
//		$posts = $posts->findAll()->where('state = %i', 1)->or('state = %i', 3)->orderBy('date DESC')->fetchAll();

		$posts = new Posts();
		$vp = new VisualPaginator($this, 'vp');

		$vp->paginator->itemsPerPage = 10;
        $vp->paginator->itemCount = $posts->count();
        $posts = $posts->findAllFrontend($vp->paginator->offset, $vp->paginator->itemsPerPage)->orderBy('date DESC')->fetchAll();
		$this->template->posts = $posts;

		$comments = $comments->findAllWithPosts()->fetchAll();
		$tags = $tags->findAll()->orderBy('tag DESC')->fetchAll();

		$this->template->posts = $posts;
//		$this->template->beeps = $beeps;
		$this->template->comments = $comments;
		$this->template->tags = $tags;
		$this->invalidateControl();
	}
    
    

    public function renderNewPost($data)
	{		
		$tags = new Tags();
		$this->template->tags = $tags->findAll()->fetchAll();
	}



    public function renderEditPost($id = 0) {
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
			//$row["date"] = strftime("%Y-%m-%d", $row["date"]);
			//ndebug::dump(strftime("%Y-%m-%d", $row["date"]));
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



    public function renderEditTag($id = 0)
	{
		$id = $this->getParam('id');
		
		$tags = new Tags();
        $tag = $tags->find($id)->fetch();
        $this->template->tag = $tag;	

		$form = $this['tagForm'];
		if (!$form->isSubmitted()) {
			if (!$tag) {
				throw new BadRequestException('Record not found');
			}			
			$form->setDefaults($tag);
		}
	}


	
	/**
	 * New post form component factory.
	 * @return mixed
	 */
	protected function createComponentPostForm()
	{
		$action = array(
	    	'1' => 'Post',
	    	'2' => 'Draft',
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


		$form->addGroup()->setOption('container', NHtml::el('div')->id('main'));
			$form->addText('title', 'Title');
				//->addRule(NForm::FILLED, 'Nezapomeňte titulek.');

			// $form->addTextarea('perex', 'Perex');

			$form->addTextarea('body', 'Body *')
				->addRule(NForm::FILLED, 'Dont forget the post body.')
				->getControlPrototype()->class = "editor";

			//$form->addText('date', 'Pubish time');

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
            
            if($this->settings['comments_enabled']){                
                $form->addCheckbox('comments_disabled', 'Disable comments for this posts');
            }            

			$form->addSubmit('send', 'Save post')->onClick[] = array($this, 'sendPostClicked');			
		
		return $form;
	}
	

    
    /**
	 * Post delete form component factory.
	 * @return mixed
	 */
	protected function createComponentDeletePostForm()
	{
		$form = new NAppForm;		
		
		$form->addSubmit('delete', 'Yes delete!')->getControlPrototype()->class('default');
		$form->addSubmit('cancel', 'Cancel')->setValidationScope(NULL)->onClick[] = array($this, 'CancelClicked');
		$form->onSubmit[] = array($this, 'deletePostFormSubmitted');		

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
	 * Tag form component factory.
	 * @return mixed
	 */
	protected function createComponentTagForm()
	{
		$form = new NAppForm;
		$form->addText('tag', 'Tag');
		$form->addSubmit('send', 'Save tag')->onClick[] = array($this, 'sendTagClicked');
		$form->addSubmit('cancel', 'Cancel')->setValidationScope(NULL)->onClick[] = array($this, 'CancelClicked');

		return $form;
	}    



   /**
	* Tag form clicked.
	* 
	*/
    public function sendTagClicked(NSubmitButton $button)
    {
    	if ($button->getForm()->getValues()){
    		$id = (int) $this->getParam('id');
			$tags = new Tags;
			
			$tags->update($id, $button->getForm()->getValues());
			$this->flashMessage('Tag has been updated.');
			$this->redirect('AdminPosts:archives');			
    	}
    }



   /**
	* Cancel clicked.
	* 
	*/
    public function CancelClicked(NSubmitButton $button)
    {    	
		$this->redirect('AdminPosts:archives');    	
    }
    
		
}
