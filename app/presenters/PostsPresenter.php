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
		$this->template->post = $post;

	}




	public function renderTag($tag_url){
		
		$tags = new Tags();
		$tag = $tags->findByUrl($tag_url)->fetch();

		$posts = new Posts();
		$posts = $posts->findAllByTagId($tag->id)->fetchAll();
		$this->template->posts = $posts;

	}






}
