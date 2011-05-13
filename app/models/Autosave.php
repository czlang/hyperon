<?php



/**
 * Autosave model.
 */
class Autosave extends NObject
{
	/** @var string */
	private $table = 'autosave';

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


	
	public function find($post_id)
	{
		return $this->connection->select('*')->from($this->table);
	}	

	

	public function autosave($post_id, $txt)
	{
		$data = array('post_id' => $post_id, 'text' => $txt);

		$autosave_exists = $this->find($post_id)->where('post_id = %i', $post_id)->fetchSingle();

		if($autosave_exists){
			return $this->connection->update($this->table, $data)->where('post_id=%i', $post_id)->execute();
		}
			else{
				return $this->connection->insert($this->table, $data)->execute(dibi::IDENTIFIER);
			}		
	}

}
