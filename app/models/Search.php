<?php





/**
 * Search model.
 */
class Search extends NObject
{
	/** @var string */
	

	/** @var DibiConnection */
	private $connection;
	
	

	public static function initialize()
	{
		dibi::connect(NEnvironment::getConfig('database'));
	}



	public function __construct()
	{
		$this->connection = dibi::getConnection();
	}


	public function lang()
	{
		$lang = (LANG) ? '_'.LANG : '';
		return $lang;
	}
	
	
	
	public function logSearch($s_query)
	{
		$data = array('search_query' => $s_query, 'search_time' => time());
		$this->connection->insert('search_log', $data)->execute(dibi::IDENTIFIER);
	}
	
	

	public function fulltextSearch($s_query)
	{	
		$search_result_posts = array();
		$search_result_pages = array();

		$search_result_posts = dibi::query("
			SELECT 
				url,
				title, 
				body,
				date
			FROM posts 	WHERE state = 1 AND
			    MATCH(
					title, 
					body
			    )
			    AGAINST ('" . $s_query  . "' IN BOOLEAN MODE)
			    ORDER BY 5 * MATCH(
					title, 
					body
			    ) 
			    AGAINST ('" . $s_query  . "') 
				+ MATCH(
					title, 
					body
				) 
				AGAINST ('" . $s_query  . "') DESC
		")->fetchAll();

		$search_result = array_merge($search_result_posts, $search_result_pages);
		
		$this->logSearch($s_query);
		
		return $search_result;
	}
	
	
	
	
	public function adminFulltextSearch($s_query)
	{		
		$search_result_posts = dibi::query("
			SELECT
					id,
					post_date as date,
					post_url,
					post_url_en,
					post_title,
					post_title_en,  
			    	post_body,
			    	post_body_en
			    FROM posts
			    WHERE MATCH(
			    	post_title,
					post_title_en,  
			    	post_body,
			    	post_body_en
			    ) 
			    AGAINST ('" . $s_query  . "' IN BOOLEAN MODE)
			    ORDER BY 5 * MATCH(
			    	post_title,
					post_title_en,  
			    	post_body,
			    	post_body_en
			    ) 
			    AGAINST ('" . $s_query  . "') 
				+ MATCH(
			    	post_title,
					post_title_en,  
			    	post_body,
			    	post_body_en
				) 
				AGAINST ('" . $s_query  . "') DESC
		")->fetchAll();
		
		$search_result_pages = dibi::query("
			SELECT
					id,
					page_date as date,
					page_url,
					page_url_en,
					page_title,
					page_title_en,					
			    	page_body,
			    	page_body_en			    	
			    FROM pages
			    WHERE MATCH(
					page_title,
					page_title_en,					
			    	page_body,
			    	page_body_en
			    ) 
			    AGAINST ('" . $s_query  . "' IN BOOLEAN MODE)
			    ORDER BY 5 * MATCH(
					page_title,
					page_title_en,					
			    	page_body,
			    	page_body_en
			    ) 
			    AGAINST ('" . $s_query  . "') 
				+ MATCH(
					page_title,
					page_title_en,					
			    	page_body,
			    	page_body_en
				) 
				AGAINST ('" . $s_query  . "') DESC
		")->fetchAll();		
			
		$search_result = array_merge($search_result_posts, $search_result_pages);
		
		return $search_result;
	}


}
