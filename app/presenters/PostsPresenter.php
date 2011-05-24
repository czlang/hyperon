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

		if($user->isLoggedIn() AND $post['state'] == 3){
			$this->template->post = $post;
		}
		elseif($post AND $post['state'] != 3){
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
			
			$vp = new VisualPaginator($this, 'vp');
			
			$vp->paginator->itemsPerPage = 10;
	        $vp->paginator->itemCount = $posts->countPostsByTag($tag->id);

	        $posts = $posts->findAllByTagId($tag->id, $vp->paginator->offset, $vp->paginator->itemsPerPage)->where(' ( state = %i', 1)->or('state = %i ) ', 2)->orderBy('date DESC')->fetchAll();
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

        $user = NEnvironment::getUser();        

        $logged_user_realname = "";
        
		if ($user->isLoggedIn()) {
            $logged_user = $user->getIdentity()->getData();
            $logged_user_realname = $logged_user['realname'];
		}
        
		$form = new NAppForm();

        $form->addGroup();
		$form->addText('author', 'Vaše jméno *')
            ->addRule(NForm::FILLED, 'Dont forget the post body.')
                ->setValue($logged_user_realname);

		$form->addTextarea('body', 'Komentář *')
			->addRule(NForm::FILLED, 'Dont forget the post body.');		

		$form->addHidden('post_id', '')->setValue($post_id);
        
        $form->addGroup()
            ->setOption('container', NHtml::el('div')->class('nospam'));
      
            $form->addText('nospam', 'Fill in „nospam“')
                ->addRule(NForm::FILLED, 'You are looking like a spambot!')
                ->addRule(NForm::EQUAL, 'You are looking like a spambot!', 'nospam');

        $form->addGroup();
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
			$this->flashMessage('Komentář odeslán.');			
            $this->redirectUri($this->link('//Posts:post', $post_url) . '#comments');
    	}
    }
	
		
	


}
