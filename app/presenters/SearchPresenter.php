<?php

/**
 * 
 *
 * @copyright  Copyright (c) 2009 Peter Lang
 * @package    
 */



/**
 * Search presenter.
 *
 * @author     Peter Lang
 * @package    
 */
final class SearchPresenter extends BasePresenter
{

	public function renderResult($s_query)
	{		
		$this->template->title = "Výsledky hledání";
		
		$search = new Search;
 		$search_result = ($search->fulltextSearch($s_query));
 		
		$this->template->search_query = $s_query;
		$this->template->search_results_count = count($search_result);
		$this->template->search_result = $search_result;

		$tags = new Tags();
		$all_tags = $tags->findAll()->fetchAll();
		$this->template->all_tags = $all_tags;
	}
		
}
