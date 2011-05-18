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
class HomepagePresenter extends BasePresenter
{

	public function renderDefault($exception)
	{
        $users = new Users();
        $this->template->users = $users->findAll()->fetchAll();

		$posts = new Posts();
		$posts = $posts->findAllFrontend()->where('state = %i', 1)->or('state = %i', 2)->orderBy('date DESC')->fetchAll();
		$this->template->posts = $posts;

	}




}
