<?php





/**
 * Settings model.
 */
class Settings extends NObject
{

	/** @var string */
	private $table = 'settings';

    
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

	
	
	public function findAll()
	{	
		return $this->connection->select('*')->from($this->table);
	}
	

    	

	public function find($name)
	{		
		return $this->connection->select('*')->from($this->table)->where('name=%s', $name);
	}
	
	

	
	public function update(array $data)
	{
		//@TODO: cant do this better? without foreach and with dibi type identifier??
		foreach ($data as $key=>$value) {
			dibi::query('UPDATE ' .  $this->table . ' SET `value` = "' . $value . '" WHERE `name` = "' . $key . '"');
  		}
		
	}


}
