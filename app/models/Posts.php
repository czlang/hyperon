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
	
	

	public static function initialize()
	{
		dibi::connect(Environment::getConfig('database'));
	}



	public function __construct()
	{
		$this->connection = dibi::getConnection();
	}

	
	
	public function count()
	{
		return count($this->connection->select('*')->from($this->table));
	}
	
	
	
	public function applyLimit($offset, $itemsPerPage)
	{		
		return $this->connection->select('*')->from($this->table)->limit($offset . ', ' . $itemsPerPage);	
		
	}
	
	

	public function findAll()
	{
		return $this->connection->select('*')->from($this->table);
	}


	public function findAllFrontend()
	{
		return $this->connection
			->select('posts.*, users.*')
			->from($this->table)
				->join('users')
				->on('posts.author_id = users.id')
				->where('state = %i', 1)
				->orderBy('date DESC');
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
			post_title as title, 
			post_date as pubDate, 
			post_url as link, 
			post_body as description
		')->from($this->table);
	}
	


	public function find($id)
	{
		return $this->connection->select('*')->from($this->table)->where('id=%i', $id);
	}

	
	
	public function findId($post_url)
	{
		return $this->connection->select('id')->from($this->table)->where('post_url=%s', $post_url);
	}
	
	
	
	
	public function findUrl($post_cid)
	{
		return $this->connection->select('post_url')->from($this->table)->where('id=%i', $post_cid);
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
	
	

	public function update($id, array $data)
	{		
		$data['post_url'] = String::webalize($data['post_title']);	
			
		return $this->connection->update($this->table, $data)->where('id=%i', $id)->execute();
	}



	public function insert(array $data)
	{
		$data['date'] = time();
		$data['url'] = NString::webalize($data['title']);

		return $this->connection->insert($this->table, $data)->execute(dibi::IDENTIFIER);
	}



	public function delete($id)
	{
		return $this->connection->delete($this->table)->where('id=%i', $id)->execute();
	}

}
