<?php





/**
 * Tags model.
 */
class Tags extends NObject
{
	/** @var string */
	private $table = 'tags';

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




	public function findByUrl($tag_url)
	{	
		return $this->connection->select('*')->from($this->table)->where('tag_url = %s', $tag_url);
	}



	

}
