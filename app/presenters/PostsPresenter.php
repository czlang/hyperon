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

		if($post){
			$this->template->post = $post;
		}
			else{
				throw new NBadRequestException(404);
			}
	}




	public function renderTag($tag_url){		
		$tags = new Tags();
		$tag = $tags->findByUrl($tag_url)->fetch();
		$this->template->tag = $tag;

		$posts = new Posts();
		$posts = $posts->findAllByTagId($tag->id)->and('( posts.state = %i', 1)->or('posts.state = %i )', 2)->orderBy('date DESC')->fetchAll();
		$this->template->posts = $posts;
	}






}
