<?php





/**
 * Beeps model.
 */
class Beeps extends NObject
{
	/** @var string */
	private $table = 'beeps';

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
		return $this->connection->select('beeps.*, users.username')->from($this->table)
			->join('users')
			->on('beeps.author_id = users.id');
	}



	public function find($id)
	{
		return $this->connection->select('*')->from($this->table)->where('id=%i', $id);
	}


	

	public function update($id, array $data)
	{
		return $this->connection->update($this->table, $data)->where('id=%i', $id)->execute();
	}



	public function insert(array $data)
	{
		$data['date'] = time();			
		return $this->connection->insert($this->table, $data)->execute(dibi::IDENTIFIER);
	}



	public function delete($id)
	{
		return $this->connection->delete($this->table)->where('id=%i', $id)->execute();
	}

}
