<?php

/**
 * My NApplication
 *
 * @copyright  Copyright (c) 2010 John Doe
 * @package    MyApplication
 */


/**
 * Base class for all application presenters.
 *
 * @author     John Doe
 * @package    MyApplication
 */
 
final class PostsPresenter extends BasePresenter
{



	public function renderPost($post_url){
		$posts = new Posts();
		$post = $posts->findSingleFrontend($post_url)->fetch();
	
		$comments = new Comments();
		$comments = $comments->findAllByPostId($post['id'])->fetchAll();

		$user = NEnvironment::getUser();

		if($user->isLoggedIn() AND $post["state"] == 3){
			$this->template->post = $post;
		}
		elseif($post AND $post["state"] != 3){
			$this->template->post = $post;
			$this->template->comments = $comments;
		}
		else{
			throw new NBadRequestException(404);
		}
	}




	public function renderTag($tag_url){		
		$tags = new Tags();
		$tag = $tags->findByUrl($tag_url)->fetch();

		if($tag){
			$this->template->tag = $tag;
			$posts = new Posts();
			$posts = $posts->findAllByTagId($tag->id)->and('( posts.state = %i', 1)->or('posts.state = %i )', 2)->orderBy('date DESC')->fetchAll();
			$this->template->posts = $posts;
		}
		else{
			throw new NBadRequestException(404);
		}		
	}





	/**
	 * New comment form component factory.
	 * @return mixed
	 */
	protected function createComponentCommentForm()
	{
		$post_url = $this->getParam('post_url');		
		if(isset($post_url)){
			$posts = new Posts();
			$post_id = $posts->findId($post_url)->fetchSingle();
		}

		$form = new NAppForm();
/*
		$renderer = $form->renderer;
		$renderer->wrappers['group']['container'] = NULL;
		$renderer->wrappers['pair']['container'] = NULL;
		$renderer->wrappers['label']['container'] = '';
		$renderer->wrappers['controls']['container'] = '';
		$renderer->wrappers['control']['container'] = 'p';
		$renderer->wrappers['control']['errors'] = TRUE;
*/
		$form->addText('author', 'Vaše jméno');

		$form->addTextarea('body', 'Body *')
			->addRule(NForm::FILLED, 'Dont forget the post body.');		

		$form->addHidden('post_id', '')->setValue($post_id);

		$form->addSubmit('send', 'Odeslat')->onClick[] = array($this, 'sendCommentClicked');			
		
		return $form;
	}
	


   /**
	* New comment form clicked.
	* 
	*/
    public function sendCommentClicked(NSubmitButton $button)
    {
		$post_url = $this->getParam('post_url');

    	if ($button->getForm()->getValues()){    		
			$comments = new Comments();	
			$comments->insert($button->getForm()->getValues());
			$this->flashMessage('Posted!.');
			$this->redirect('Posts:post', $post_url);			
    	}
    }
	
		
	


}
