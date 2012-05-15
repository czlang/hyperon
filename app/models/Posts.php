<?php

/**
 * Posts model.
 */
class Posts extends NObject
{
	/** @var string */
	private $table = 'posts';

	/** @var DibiConnection */
	private $connection;
	
	

	public static function initialize() {
		dibi::connect(Environment::getConfig('database'));
	}


	public function __construct() {
		$this->connection = dibi::getConnection();
	}

	
	public function count() {
		return count($this->connection->select('*')->where('state = %i', 1)->from($this->table));
	}


	public function countPostsByTag($tag_id) {
		return count($this->connection->select('*')
					->from($this->table)
					->join('posts_tags')
					->on('posts_tags.post_id = posts.id')					
					->where('posts_tags.tag_id = %i', $tag_id));
	}
	
	
	public function applyLimit($offset, $itemsPerPage) {	
		return $this->connection
			->select('posts.*, users.id as user_id, users.username as username, users.realname as realname')
			->from($this->table)
				->leftJoin('users')
				->on('posts.author_id = users.id')
				->limit($offset . ', ' . $itemsPerPage);		
	}
	

	public function findAll() {
		return $this->connection->select('*')->from($this->table);
	}


	public function findAllFrontend($offset, $itemsPerPage) {
		return $this->connection
			->select('posts.*, users.id as user_id, users.username as username, users.realname as realname')
				->select(
					dibi::select('count(*)')
	                ->from('comments')
	                ->where('comments.post_id = posts.id')
				)->as('comments_count')
				->select(
						dibi::select('GROUP_CONCAT(tag, "-", tag_url)')
							->from('tags')
							->leftJoin('posts_tags')
								->on('tags.id = posts_tags.tag_id')
							->where('posts.id = posts_tags.post_id ')
					)->as('tags')
			->from($this->table)
				->leftJoin('users')
				->on('posts.author_id = users.id')
				->limit($offset . ', ' . $itemsPerPage);
	}


	public function findSingleFrontend($url) {
		return $this->connection
			->select('posts.*, users.id as user_id, users.username as username, users.realname as realname')
				 ->select(
				 	dibi::select('count(*)')
	                 ->from('comments')
	                 ->where('comments.post_id = posts.id')
				 )->as('comments_count')
 				->select(
						dibi::select('GROUP_CONCAT(tag, "-", tag_url)')
							->from('tags')
							->leftJoin('posts_tags')
								->on('tags.id = posts_tags.tag_id')
							->where('posts.id = posts_tags.post_id ')
					)->as('tags')
			->from($this->table)
				->leftJoin('users')
				->on('posts.author_id = users.id')
				->where('url = %s', $url);
	}



	
	
	public function findLatest($limit)
	{
		return $this->connection->select('*')->from($this->table)->orderBy('post_date DESC')->limit($limit);
	}	
	


	public function findAllId()
	{
		return $this->connection->select('id')->from($this->table);
	}
	
	
	
	
	public function findAllForRSS()
	{
		return $this->connection->select('
			id, 
			title as title, 
			date as pubDate, 
			url as link,
			body as description
		')->from($this->table);
	}
	


	public function findAllByTagId($tag_id, $offset, $itemsPerPage)
	{
		return $this->connection
			->select('posts.*, users.username, users.realname')
				->select(
					dibi::select('count(*)')
	                ->from('comments')
	                ->where('comments.post_id = posts.id')
				)->as('comments_count')
				->select(
						dibi::select('GROUP_CONCAT(tag, "-", tag_url)')
							->from('tags')
							->leftJoin('posts_tags')
								->on('tags.id = posts_tags.tag_id')
							->where('posts.id = posts_tags.post_id ')
					)->as('tags')
			->from($this->table)
				->join('posts_tags')
					->on('posts_tags.post_id = posts.id')
				->leftJoin('users')
					->on('posts.author_id = users.id')
				->where('posts_tags.tag_id = %i', $tag_id)
				->limit($offset . ', ' . $itemsPerPage);
	}



	public function find($id)
	{
		return $this->connection->select('*')->from($this->table)->where('id=%i', $id);
	}

	
	
	public function findId($post_url)
	{
		return $this->connection->select('id')->from($this->table)->where('url=%s', $post_url);
	}
	
	
	
	
	public function findUrl($url)
	{
		return $this->connection->select('url')->from($this->table)->where('url=%s', $url);
	}
	
	

	public function findUrlById($id)
	{
		return $this->connection->select('post_url')->from($this->table)->where('id=%i', $id);
	}	


	
	public function findPostTitle($post_url)
	{		
		return $this->connection->select('post_title')->from($this->table)->where('post_url=%s', $post_url);
	}
		
	
	
	public function findPostByUrl($post_url)
	{
		return $this->connection->select('*')->from($this->table)->where('post_url=%s', $post_url);
	}
	



	public function getMaxId()
	{			
		return $this->connection->select('id')->from($this->table)->orderBy('id DESC')->fetchSingle();
	}

	


	public function insert(array $data) {
		unset($data['id']);

		$data['id'] = $this->getMaxId() + 1;

		if($data['date'] == ""){
			$data['date%sql'] = 'NOW()';
			unset($data['date']);
		}
		$data['url'] = NString::webalize($data['title']);
		$url_already_exists = $this->findUrl($data['url'])->fetchSingle();
		if($url_already_exists){
			$data['url'] = $url_already_exists . "-" . time();
		}
		if( isset($data['tags']) AND ($data['tags'] != '') ){
			$this->solveTags($data);		
		}
		unset($data['tags']);				
		//ndebug::dump($data);
		//die;
		return $this->connection->insert($this->table, $data)->execute(dibi::IDENTIFIER);
	}



	public function solveTags($data)
	{		
		$tags_md = new Tags();
		$posts_tags_md = new PostsTags();

		if(isset($data["id"])){
			$post_id = $data["id"];
			$posts_tags_md->deleteAllByPostId($data["id"]);
		}
		else{
			$post_id = $this->getMaxId() + 1;
		}

		$tags = (explode(",", $data['tags']));

		foreach ($tags as $key=>$value) {
			$existing_tag_id = $tags_md->findTagIdByTag($value) + 0;
			if($existing_tag_id){
				$posts_tags_data = array('tag_id' => $existing_tag_id, 'post_id' => $post_id);
				$posts_tags_md->insert($posts_tags_data);
			}
				else{
					$new_tags = array('tag' => $value, 'tag_url' => NString::webalize($value));
					$tags_md->insert($new_tags);
					$new_tags_ids = $tags_md->findTagsIds($new_tags['tag']);
					$posts_tags_ids = array('tag_id' => $new_tags_ids, 'post_id' => $post_id);
					$posts_tags_md->insert($posts_tags_ids);
				}
 			}
	}




	public function update($id, array $data) {		
		//$data["date"] = strtotime($data["date"]);
		// ndebug::dump(strftime("%Y-%m-%d", $data["date"]));
		// die;
		$data['url'] = NString::webalize($data['title']);	
		if(isset($data['tags'])){
			$this->solveTags($data);		
		}	
		unset($data['tags']);
		return $this->connection->update($this->table, $data)->where('id=%i', $id)->execute();
	}



	public function delete($id)
	{
		return $this->connection->delete($this->table)->where('id=%i', $id)->execute();
	}

}
