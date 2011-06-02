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



	public function find($id)
	{	
		return $this->connection->select('*')->from($this->table)->where('id = %i', $id);
	}



	public function findByUrl($tag_url)
	{	
		return $this->connection->select('*')->from($this->table)->where('tag_url = %s', $tag_url);
	}




	public function findTagIdByTag($tag)
	{	
		return $this->connection->select('id')->from($this->table)->where('tag = %s', $tag)->fetchSingle();
	}




	public function findTagsIds($tag)
	{	
		return $this->connection->select('id')->from($this->table)->where('tag = %s', $tag)->fetchSingle();
	}




	public function insert(array $data)
	{
		return $this->connection->insert($this->table, $data)->execute(dibi::IDENTIFIER);
	}



	public function delete($id)
	{
		return $this->connection->delete($this->table)->where('id=%i', $id)->execute();
	}



	public function update($id, array $data)
	{
		$data["tag_url"] = NString::webalize($data["tag"]);
		return $this->connection->update($this->table, $data)->where('id=%i', $id)->execute();
	}

}
