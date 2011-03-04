<?php


/**
 * Users authenticator.
 */
class Users extends NObject implements IAuthenticator
{

    
	/** @var string */
	private $table = 'users';

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
	
	
	
	public function find($id)
	{
		return $this->connection->select('*')->from($this->table)->where('id=%i', $id);
	}
	
	
	
	public function findByUsername($username)
	{
		return $this->connection->select('*')->from($this->table)->where('username=%s', $username);
	}
	
	
	
	public function findIdByUsername($username)
	{
		return $this->connection->select('id')->from($this->table)->where('username=%s', $username);
	}
	
	
	
	public function getUserNames()
	{
		return $this->connection->select('id, username')->from($this->table);
	}
	
	
	
	public function getUsernameById($id)
	{
		return $this->connection->select('username')->from($this->table)->where('id=%i', $id);
	}
	
	
	
	public function getAvatarById($id)
	{
		return $this->connection->select('avatar')->from($this->table)->where('id=%i', $id);
	}
	
	

	public function getFollowNewReset($username)
	{
		return $this->connection->select('follow_new_reset')->from($this->table)->where('username=%s', $username);
	}



	public function doFollow($follower_id, $followed_id)
	{
		$data = array('follower_id' => $follower_id, 'followed_id' => $followed_id);
		return $this->connection->insert('follow', $data)->execute(dibi::IDENTIFIER);
	}



	public function doUnfollow($follower_id, $followed_id)
	{
		return $this->connection->delete('follow')->where('follower_id=%i', $follower_id)->and('followed_id=%i', $followed_id)->execute();
	}



	public function findFollowedIds($user_id)
	{		
		return $this->connection->select('followed_id')->from('follow')->where('follower_id = %i', $user_id);
	}

	
	
	public function update($id, array $data)
	{	
		//$username = mb_strtolower($data['username'], 'UTF-8');
		/*
		if(!isset($data['password'])){
			unset($data['password2']);			
		}
			else{
				$data['password'] = sha1($username . $data['password']);
			}
		*/
        unset($data['password']);
		unset($data['password2']);
		unset($data['user_id']);
		/*
		if($data['avatar'] == ''){
			unset($data['avatar']);			
		}
			else{
				$filename = String::webalize($data['avatar']->name, '.');
				$data['avatar'] = mb_strtolower(String::webalize($data['username']), 'UTF-8') . '_' . $filename;
			}
		*/			
		return $this->connection->update($this->table, $data)->where('id=%i', $id)->execute();
	}
	


	public function insert(array $data)
	{		
		//$username = mb_strtolower($data['username'], 'UTF-8');
        //$data['username'] = '';
		$data['password'] = sha1($data['email'] . $data['password']);
		$data['register_time'] = time(); 
		$data['realname'] = '';
		$data['role'] = 'member';
		$data['avatar'] = 'default.png';
		$data['about'] = '';		
		$data['public_email'] = '0';
		$data['send_news'] = '0';
		unset($data['password2']);
		unset($data['nospam']);
		return $this->connection->insert($this->table, $data)->execute(dibi::IDENTIFIER);		
	}

    
    public function insertTwitterUser(array $data)
	{		
		$data['username'] = $data['screen_name'];
		$data['password'] = sha1($data['screen_name'] . $data['oauth_token_secret']);
		$data['register_time'] = time(); 
		$data['realname'] = '';
		$data['role'] = 'member';
		//$data['avatar'] = 'default.png';
		$data['about'] = '';
		$data['reviews_count'] = '0';
		$data['public_email'] = '0';
		$data['send_news'] = '0';
        $data['twitter_user_id'] = $data['user_id'];
        $data['twitter_token'] = $data['oauth_token'];
        $data['twitter_token_secret'] = $data['oauth_token_secret'];

        unset($data['screen_name']);
        unset($data['oauth_token']);
        unset($data['oauth_token_secret']);
        unset($data['user_id']);				
		unset($data['nospam']);
		return $this->connection->insert($this->table, $data)->execute(dibi::IDENTIFIER);		
	}




    public function insertFacebookUser(array $data)
	{
		$data['username'] = $data['name'];
		$data['password'] = sha1($data['name'] . $data['access_token']);
		$data['register_time'] = time();
		$data['realname'] = '';
		$data['role'] = 'member';
		$data['about'] = '';
		$data['reviews_count'] = '0';
		$data['public_email'] = '0';
		$data['send_news'] = '0';
        $data['twitter_user_id'] = NULL;
        $data['twitter_token'] = NULL;
        $data['twitter_token_secret'] = NULL;
        $data['facebook_user_id'] = $data['uid'];
        $data['facebook_sig'] = $data['sig'];
        $data['facebook_access_token'] = $data['access_token'];


        unset($data['uid']);
        unset($data['sig']);
        unset($data['access_token']);
        unset($data['name']);
        unset($data['session_key']);
        unset($data['expires']);
        unset($data['secret']);

		return $this->connection->insert($this->table, $data)->execute(dibi::IDENTIFIER);
	}



    public function updateTwitterUser($user_id, $password, $oauth_token, $oauth_token_secret)
	{
        dibi::query("UPDATE [" . $this->table . "] SET password = '" . $password . "', twitter_token = '" . $oauth_token . "', twitter_token_secret = '" . $oauth_token_secret . "' WHERE [twitter_user_id] = %i", $user_id);
	}




    public function findTwitterUser($user_id)
	{
		return $this->connection->select('*')->from($this->table)->where('twitter_user_id = %i', $user_id);
	}



    public function findFacebookUser($user_id)
	{
		return $this->connection->select('*')->from($this->table)->where('facebook_user_id = %i', $user_id);
	}



    public function updateFacebookUser($uid, $password, $sig, $access_token)
	{
        dibi::query("UPDATE [" . $this->table . "] SET password = '" . $password . "', facebook_sig = '" . $sig . "', facebook_access_token = '" . $access_token . "' WHERE [facebook_user_id] = %i", $uid);
	}


	
	public function delete($id)
	{
		return $this->connection->delete($this->table)->where('id=%i', $id)->execute();
	}
	


	public function updateLastLogin($id)
	{
		dibi::query("UPDATE [" . $this->table . "] SET last_login = ". time() . " WHERE [id] = %i", $id);
	}



	public function makeOnline($id)
	{
		dibi::query("UPDATE [" . $this->table . "] SET online = 1 WHERE [id] = %i", $id);
	}



	public function makeOffline($id)
	{
		dibi::query("UPDATE [" . $this->table . "] SET online = 0 WHERE [id] = %i", $id);
	}



	public function setFollowNewReset($username)
	{
		dibi::query("UPDATE [" . $this->table . "] SET follow_new_reset = " . time() . " WHERE [username] = %s", $username);
	}

	

	/**
	 * Performs an authentication
	 * @param  array
	 * @return void
	 * @throws AuthenticationException
	 */
	public function authenticate(array $credentials)
	{
		$email = $credentials[self::USERNAME]; //@FIXME username is email here
		$password = $credentials[self::PASSWORD];

		$row = dibi::select('*')->from('users')->where('email=%s', $email)->fetch();

        //var_dump($row);

		if (!$row) {
			throw new NAuthenticationException("Uživatel '$email' nenalezen.", self::IDENTITY_NOT_FOUND);
		}
  

		if ($row->password !== $password) {
			throw new NAuthenticationException("Neplatné heslo.", self::INVALID_CREDENTIAL);
		}

		unset($row->password);
			
		return new NIdentity($row->username, $row->role, $row);

		
	}



}
