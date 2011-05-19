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

	

	public function findAllByPostId($post_id)
	{
		return $this->connection->select('*')->from($this->table)->where('post_id = %i', $post_id)->orderBy('time DESC');
	}



	public function insert(array $data)
	{				
		$data['time'] = time();		
		$data['visible'] = 1;
		return $this->connection->insert($this->table, $data)->execute(dibi::IDENTIFIER);
	}
	
}
