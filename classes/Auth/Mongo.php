<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Date: 10/24/14
 * Time: 7:15 PM
 * Something meaningful about this file
 *
 */

class Auth_Mongo extends Auth
{
	protected $_connection = null;
	protected $_database = null;
	protected $_collection = null;

	public function __construct($config = array())
	{
		parent::__construct($config); // TODO: Change the autogenerated stub

		// Get mongo settings
		$_settings = Arr::get($config, 'mongo', array());
		$this->_connection = new MongoClient();

		$database = Arr::path($_settings, 'database');

		$this->_database = $this->_connection->$database;
	}

	protected function _login($username, $password, $remember)
	{
		// TODO: Implement _login() method.
		if (is_string($password))
		{
			// Create a hashed password
			$password = $this->hash($password);
		}

		$this->_collection = $this->_database->user;
		//Search for the user
		$cursor = $this->_collection->find(array('username' => $username));
		switch (count($cursor)) {
			case 0:
				break;
			case 1:
				$document = iterator_to_array($cursor);
				if (empty($document)) {
					// Login failed
					return FALSE;
					//404
				} else {

					$document_ids = array_keys($document);
					$user_data = $document[$document_ids[0]];
					unset($user_data['_id']);

					$_username = Arr::path($user_data, 'username', FALSE);
					$_password = Arr::path($user_data, 'password', FALSE);
					$_display_name = Arr::path($user_data, 'display_name', FALSE);
					$_first_name = Arr::path($user_data, 'first_name', FALSE);
					$_last_name = Arr::path($user_data, 'last_name', FALSE);

					if (!(empty($_username)) && $username === $_username)
					{
						if (!empty($_password) && ($password === $_password)) {
							// Complete the login
							return $this->complete_login($user_data);
						}
					}
				}
				break;
			default:
				foreach ($cursor as $document) {
					$this->output[] = $document;
				}
		}

		// Login failed
		return FALSE;
	}

	public function password($username)
	{
		// TODO: Implement password() method.
	}

	public function check_password($password)
	{
		// TODO: Implement check_password() method.
	}

	public static function instance()
	{
		return parent::instance(); // TODO: Change the autogenerated stub
	}

	public function get_user($default = NULL)
	{
		return parent::get_user($default); // TODO: Change the autogenerated stub
	}

	public function login($username, $password, $remember = FALSE)
	{
		return parent::login($username, $password, $remember); // TODO: Change the autogenerated stub
	}

	public function logout($destroy = FALSE, $logout_all = FALSE)
	{
		return parent::logout($destroy, $logout_all); // TODO: Change the autogenerated stub
	}

	public function logged_in($role = NULL)
	{
		return parent::logged_in($role); // TODO: Change the autogenerated stub
	}

	public function hash_password($password)
	{
		return parent::hash_password($password); // TODO: Change the autogenerated stub
	}

	public function hash($str)
	{
		return parent::hash($str); // TODO: Change the autogenerated stub
	}

	protected function complete_login($user)
	{
		return parent::complete_login($user); // TODO: Change the autogenerated stub
	}


}
