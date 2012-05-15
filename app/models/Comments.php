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


   	public function find($id)
	{
		return $this->connection->select('*')->from($this->table)->where('id=%i', $id);
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
		return $this->connection->select('*')
			->from($this->table)
			->where('post_id = %i', $post_id)
			->orderBy('sort, time ASC')
			->fetchAll();		
	}


	public function updateSort($post_id, $from)
	{	
		return dibi::query(
			"UPDATE ".$this->table." SET sort = sort + 1 WHERE post_id=%i", $post_id, "AND sort > %i", $from
			);	
	}


	public function getChildSortMax($parent_id)
	{
		return $this->connection->select('MAX(sort)')
			->from($this->table)
			->where('parent_id=%i', $parent_id)
			->fetchSingle();
	}


	public function getMaxSort($post_id)
	{
		return $this->connection->select('MAX(sort)')
			->from($this->table)
			->where('post_id=%i', $post_id)
			->fetchSingle();
	}


	public function getSortDepth($id)
	{
		return $this->connection->select('sort, depth')
			->from($this->table)
			->where('id=%i', $id)
			->fetch();
	}


	public function help($data, $post_id)
	{
		return $this->connection->select('id, MIN(sort) -1 as sort, '.$data["depth"].' as depth')
			->from($this->table)
			->where('sort > '.$data["sort"].'')
			->and('depth <= '.$data["depth"].'')
			->and('post_id = %i', $post_id)
			->fetch();
	}

	public function insert(array $data)
	{	
		/*
		 this threaded discuss solution inspired by 
		 http://php.vrana.cz/diskuse-s-reakcemi.php
		 thanks a lot
		 */

		if($data["parent_id"] > 0){
			$parent = $this->find($data["parent_id"])->fetch();
			$parent_sort_depth = $this->getSortDepth($data["parent_id"]);
			$help = $this->help($parent_sort_depth, $parent["post_id"]);

			if($help["sort"]){				
				$update_sort = $this->updateSort($data["post_id"], $help["sort"]);
				$data["sort"] = $help["sort"] + 1;
			}
				else{
					$max_sort = $this->getMaxSort($data["post_id"]);
					$data["sort"] = $max_sort + 1;
				}
			$data["depth"] = $parent["depth"] + 1;
		}

		else{
			$max_sort = $this->getMaxSort($data["post_id"]);			
			$data["depth"] = 1;
			$data["sort"] = $max_sort + 1;
		}
		$data["body"] = htmlspecialchars($data["body"], ENT_NOQUOTES);
		$data['time'] = time();		
		$data['visible'] = 1;
        unset($data['nospam']);        

        $user = NEnvironment::getUser();
        if ($user->isLoggedIn()) {
            $logged_user = $user->getIdentity()->getData();         
            $data['user_id'] = $logged_user['id'];            
        }

		return $this->connection->insert($this->table, $data)->execute(dibi::IDENTIFIER);
	}
    
    
    
    public function delete($id)
	{
		return $this->connection->delete($this->table)->where('id=%i', $id)->execute();
	}
	
}
