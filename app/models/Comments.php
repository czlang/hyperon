<?php





/**
 * Comments model.
 */
class Comments extends NObject
{
	/** @var string */
	private $table = 'comments';

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

    
    
   	public function findAll()
	{
		return $this->connection->select('*')->from($this->table);
	}

    
    
    public function findAllWithPosts()
	{
		return $this->connection->select('comments.*, posts.id as post_id, posts.url as post_url')->from($this->table)
                ->leftJoin('posts')
                    ->on('posts.id = comments.post_id')
                ->orderBy('time DESC');
	}
    
    
    
    public function countByPostId($post_id)
	{
		return count($this->connection->select('*')->from($this->table)->where('post_id = %i', $post_id));
	}
    
    

	public function findAllByPostId($post_id)
	{
		return $this->connection->select('*')->from($this->table)->where('post_id = %i', $post_id)->orderBy('time DESC');
	}



	public function insert(array $data)
	{				
		$data['time'] = time();		
		$data['visible'] = 1;
        unset($data['nospam']);
		return $this->connection->insert($this->table, $data)->execute(dibi::IDENTIFIER);
	}
    
    
    
    public function delete($id)
	{
		return $this->connection->delete($this->table)->where('id=%i', $id)->execute();
	}
	
}
