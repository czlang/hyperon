<?php





/**
 * PostsTags model.
 */
class PostsTags extends NObject
{
	/** @var string */
	private $table = 'posts_tags';

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

	
	
	
	public function findAll()
	{	
		return $this->connection->select('*')->from($this->table);
	}



	public function findAllByPostId($post_id)
	{	
		return $this->connection
			->select('tags.tag, tags.tag_url')
			->from($this->table)
				->leftJoin('posts')
					->on('posts.id = posts_tags.post_id')
				->leftJoin('tags')
					->on('tags.id = posts_tags.tag_id')
				->where('post_id = %i', $post_id);
	}




	public function deleteAllByPostId($post_id)
	{	
		dibi::query("DELETE FROM [" . $this->table . "] WHERE [post_id] = %i", $post_id);
	}



	public function insert(array $data)
	{
		return $this->connection->insert($this->table, $data)->execute();
	}



	public function delete($id)
	{
		return $this->connection->delete($this->table)->where('tag_id=%i', $id)->execute();
	}	

}
