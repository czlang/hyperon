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
final class HomepagePresenter extends BasePresenter
{

    protected function startup() {
        parent::startup();
	}


	public function renderDefault($exception) {
        $users = new Users();
        $this->template->users = $users->findAll()->fetchAll();		

   		$posts = new Posts();	
		$vp = new VisualPaginator($this, 'vp');
		
		$vp->paginator->itemsPerPage = 10;
        $vp->paginator->itemCount = $posts->count();
        $posts = $posts->findAllFrontend($vp->paginator->offset, $vp->paginator->itemsPerPage)->where('state = %i', 1)->or('state = %i', 2)->orderBy('date DESC')->fetchAll();
		$this->template->posts = $posts;
	}


}
