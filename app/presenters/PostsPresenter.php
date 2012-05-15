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

    protected function startup() {
        parent::startup();
	}
                

	public function renderPost($post_url){
		$posts = new Posts();
		$post = $posts->findSingleFrontend($post_url)->fetch();
	
		$comments = new Comments();
		$comments = $comments->findAllByPostId($post['id']);
		$comments_count = count($comments);

		$user = NEnvironment::getUser();

		if($user->isLoggedIn() AND $post['state'] == 3){
			$this->template->post = $post;
		}
		elseif($post AND $post['state'] != 3){
			$this->template->post = $post;
			$this->template->comments = $comments;
			$this->template->comments_count = $comments_count;

			if((int) $post["date"] < strtotime("-1 year")){
				$this->template->old_post = true;				
			}
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


	public function renderArchives(){
		$posts = new Posts();

		$vp = new VisualPaginator($this, 'vp');
		
		$vp->paginator->itemsPerPage = 10;
        $vp->paginator->itemCount = $posts->count();
        $posts = $posts->findAllFrontend($vp->paginator->offset, $vp->paginator->itemsPerPage)->where('state = %i', 1)->or('state = %i', 2)->orderBy('date DESC')->fetchAll();
		$this->template->posts = $posts;

		$tags = new Tags();
		$all_tags = $tags->findAll()->fetchAll();
		$this->template->all_tags = $all_tags;

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
		$form->addHidden('parent_id', '');
        
        $form->addGroup()
            ->setOption('container', NHtml::el('div')->class('nospam'));
      
            $form->addText('nospam', 'Fill in „nospam“')
                ->addRule(NForm::FILLED, 'You are looking like a spambot!')
                ->addRule(NForm::EQUAL, 'You are looking like a spambot!', 'nospam');

        $form->addGroup();
		$form->addSubmit('send', 'Odeslat komentář')->onClick[] = array($this, 'sendCommentClicked');			
		
		return $form;
	}
	


   /**
	* New comment form clicked.
	*/
    public function sendCommentClicked(NSubmitButton $button) {
		$post_url = $this->getParam('post_url');

    	if ($button->getForm()->getValues()){
			$comments = new Comments();	
			$insert_comment = $comments->insert($button->getForm()->getValues());

			$mail = new NMail();
			$mail->setFrom(''.$this->settings["web_name"].' <'.$this->settings["web_email"].'>')
				->addTo($this->settings["web_email"])
				->setSubject('Nový komentář')
    			->setBody('Nový komentář na '.$this->settings["web_name"].' '.$this->baseUri())
				->send();

			$this->flashMessage('Díky za komentář', 'info');
            $uri = NEnvironment::getHttpRequest()->getReferer();
			$uri->appendQuery(array(self::FLASH_KEY => $this->getParam(self::FLASH_KEY)));
			$uri->appendQuery('#comment'.$insert_comment.'');
            $this->redirectUri($uri->absoluteUri);
    	}
    }
	
		
	


}
