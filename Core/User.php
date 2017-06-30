<?php
namespace Core;

use Core\QB\DB;

class User
{

    static $_instance;

    protected $_tbl = 'users';  // Users table name
    protected $_tbl_roles = 'users_roles';  // Roles table name

    protected $_session = 'user';

    private $_salt = 'xQ1=\G(8E=1A~)?8;7]]E/U*,kbm=aII'; // Salt
    private $_hash_type = 'sha256'; // Hash type

    public $_info;
    public $_admin = false;

    // Для распределения прав пользователей TODO
    public $_access = [];
    public $_full_access = false;
    public $_current_access = 'no';

    function __construct()
    {
        if (APPLICATION == 'backend') {
            $this->_session = 'admin';
        }
        $this->_info = $this->get_logged_user_information();
        if ($this->_info AND $this->_info->role != 'user') {
            $this->_admin = true;
        }
        if (APPLICATION == 'backend') {
            if ($this->_admin && ($this->_info->role_id == 3 || $this->_info->role_id == 4)) {
                $this->_full_access = true;
            } else if ($this->_admin && $this->_info->role != 'user') {
                $this->_access = $this->get_access($this->_info->role_id);
            }
            $this->get_current_access();
        }
    }

    function __destruct()
    {
    }


    /**
     * Factory method for return instance of current class
     * @return User
     */
    static function factory()
    {
        if (self::$_instance == null) {
            self::$_instance = new user();
        }
        return self::$_instance;
    }


    /**
     * Get user info
     * @return bool|object|NULL
     */
    static function info()
    {
        return User::factory()->_info;
    }


    /**
     * Check is logged user admin
     * @return bool
     */
    static function admin()
    {
        return User::factory()->_admin;
    }


    /**
     * Get access for user
     * @return bool
     */
    static function access()
    {
        return User::factory()->_access;
    }


    /**
     * Get is full access for user
     * @return bool
     */
    static function god()
    {
        return User::factory()->_full_access;
    }


    /**
     * @return bool
     */
    static function caccess()
    {
        return User::factory()->_current_access;
    }


    /**
     *      If the user logged in, it will return his information, otherwise - return false
     */
    public function get_logged_user_information()
    {
        if (!isset($_SESSION[$this->_session])) {
            return false;
        }
        if ((int)$_SESSION[$this->_session] == 0) {
            return false;
        }
        $info = DB::select($this->_tbl . '.*', [$this->_tbl_roles . '.alias', 'role'])
            ->from($this->_tbl)
            ->join($this->_tbl_roles)->on($this->_tbl . '.role_id', '=', $this->_tbl_roles . '.id');
        if (APPLICATION == 'backend') {
            $info->where($this->_tbl_roles . '.alias', '!=', 'user');
        } else {
            $info->where($this->_tbl_roles . '.alias', '=', 'user');
        }
        return $info->where($this->_tbl . '.id', '=', (int)$_SESSION[$this->_session])->where($this->_tbl . '.status', '=', 1)->find();
    }


    /**
     * Get array with access for current user role
     * @param $role_id - Role ID
     * @return array
     */
    public function get_access($role_id)
    {
        $result = DB::select()->from('access')->where('role_id', '=', $role_id)->find_all();
        $arr = [];
        foreach ($result AS $obj) {
            if ($obj->edit && $obj->view) {
                $arr[$obj->controller] = 'edit';
            } else if ($obj->view) {
                $arr[$obj->controller] = 'view';
            } else {
                $arr[$obj->controller] = 'no';
            }
        }
        return $arr;
    }


    /**
     * @return string
     */
    public function get_current_access()
    {
        if (!$this->_info) {
            return 'no';
        }
        $access = $this->_access;
        if ($this->_full_access || Route::controller() == 'auth' || Route::module() == 'ajax') {
            return $this->_current_access = 'edit';
        }
        if (!isset($access[Route::controller()]) || $access[Route::controller()] == 'no') {
            return $this->_current_access = 'no';
        }
        if ($access[Route::controller()] == 'view' && Route::action() != 'index' && Route::action() != 'edit') {
            return $this->_current_access = 'no';
        }
        if ($access[Route::controller()] == 'view' && Route::action() == 'edit' && $_POST) {
            return $this->_current_access = 'no';
        }
        return $this->_current_access = $access[Route::controller()];
    }


    /**
     * @param $controller
     * @return string
     */
    public static function get_access_for_controller($controller)
    {
        $access = User::access();
        if (User::god() || $controller == 'auth' || $controller == 'ajax') {
            return 'edit';
        }
        if (!isset($access[$controller]) || $access[$controller] == 'no') {
            return 'no';
        }
        return $access[$controller];
    }


    /**
     *      Get user by login, password and status if exists
     * @param $login - user login
     * @param $password - user password
     * @param $status - user status
     */
    public function get_user_if_isset($login = null, $password = null, $status = null)
    {
        if ($login == null) {
            return false;
        }
        $result = DB::select($this->_tbl . '.*', [$this->_tbl_roles . '.alias', 'role'])
            ->from($this->_tbl)
            ->join($this->_tbl_roles)->on($this->_tbl . '.role_id', '=', $this->_tbl_roles . '.id');
        if (APPLICATION == 'backend') {
            $result->where($this->_tbl_roles . '.alias', '!=', 'user');
        } else {
            $result->where($this->_tbl_roles . '.alias', '=', 'user');
        }
        $result = $result->where($this->_tbl . '.login', '=', $login);
        if ($status !== null) {
            $result->where($this->_tbl . '.status', '=', $status);
        }
        if ($password !== null) {
            $result->where($this->_tbl . '.password', '=', $this->hash_password($password));
        }
        $user = $result->find();
        return $user;
    }

    /**
     *      Get user by email, password and status if exists
     * @param $email - user email
     * @param $password - user password
     * @param $status - user status
     */
    public function get_user_by_email($email = null, $password = null, $status = null)
    {
        if ($email == null) {
            return false;
        }
        $result = DB::select($this->_tbl . '.*', [$this->_tbl_roles . '.alias', 'role'])
            ->from($this->_tbl)
            ->join($this->_tbl_roles)->on($this->_tbl . '.role_id', '=', $this->_tbl_roles . '.id');
        if (APPLICATION == 'backend') {
            $result->where($this->_tbl_roles . '.alias', '!=', 'user');
        } else {
            $result->where($this->_tbl_roles . '.alias', '=', 'user');
        }
        $result = $result->where($this->_tbl . '.email', '=', $email);
        if ($status !== null) {
            $result->where($this->_tbl . '.status', '=', $status);
        }
        if ($password !== null) {
            $result->where($this->_tbl . '.password', '=', $this->hash_password($password));
        }
        $user = $result->find();
        return $user;
    }

    /**
     *      Get user by hash and status (0, 1) if exists
     * @param $hash - user hash
     * @param $status - user status
     */
    public function get_user_by_hash($hash = null, $status = null)
    {
        if ($hash == null) {
            return false;
        }
        $result = DB::select($this->_tbl . '.*', [$this->_tbl_roles . '.alias', 'role'])
            ->from($this->_tbl)
            ->join($this->_tbl_roles)->on($this->_tbl . '.role_id', '=', $this->_tbl_roles . '.id');
        if (APPLICATION == 'backend') {
            $result->where($this->_tbl_roles . '.alias', '!=', 'user');
        } else {
            $result->where($this->_tbl_roles . '.alias', '=', 'user');
        }
        $result = $result->where($this->_tbl . '.hash', '=', $hash);
        if ($status !== null) {
            $result->where($this->_tbl . '.status', '=', $status);
        }
        $user = $result->find();
        return $user;
    }

    /**
     *      Generate a random password
     */
    public static function generate_random_password()
    {
        return Text::random('alnum', 8);
    }

    /**
     *      Generate password hash
     * @param $password - desired password
     * @param $salt - Salt for hash. If empty - use salt default
     */
    public function hash_password($password = null, $salt = null)
    {
        if ($password == null) {
            return false;
        }
        if ($salt == null) {
            $salt = $this->_salt;
        }
        return hash($this->_hash_type, $password . $salt);
    }

    /**
     *      Update users password
     * @param $id - ID of the user who needs a new password
     * @param $password - desired password
     * @param $salt - Salt for hash. If empty - use salt default
     */
    public function update_password($id = null, $password = null, $salt = null)
    {
        if ($id == null) {
            return false;
        }
        if ($password == null) {
            return false;
        }
        return DB::update($this->_tbl)->set(['password' => $this->hash_password($password, $salt), 'updated_at' => time()])->where('id', '=', $id)->execute();
    }

    /**
     *      Compare the passwords
     * @param $password - Users entered password
     * @param $hash_from_db - Hash from the database
     * @param $salt - Salt for hash. If empty - use salt default
     */
    public function check_password($password = null, $hash_from_db = null, $salt = null)
    {
        if ($password == null) {
            return false;
        }
        if ($hash_from_db == null) {
            return false;
        }
        if ($salt == null) {
            $salt = $this->_salt;
        }
        $password_hash = hash($this->_hash_type, $password . $salt);
        if ($password_hash == $hash_from_db) {
            return true;
        }
        return false;
    }

    /**
     *      Generate users hash
     * @param $login - user login/email
     * @param $password - user password
     * @param $salt - Salt for hash. If empty - use salt default
     */
    public function hash_user($login = null, $password = null, $salt = null)
    {
        if ($password == null) {
            return false;
        }
        if ($login == null) {
            return false;
        }
        if ($salt == null) {
            $salt = $this->_salt;
        }
        $hash = hash($this->_hash_type, $login . $password . $salt);
        return $hash;
    }

    /**
     *      Checking hash...
     * @param $check_hash - verifiable hash
     * @param $login - user login/email
     * @param $password - user password
     * @param $salt - Salt for hash. If empty - use salt default
     */
    public function check_hash($checked_hash = null, $login = null, $password = null, $salt = null)
    {
        if ($checked_hash == null) {
            return false;
        }
        if ($login == null) {
            return false;
        }
        if ($password == null) {
            return false;
        }
        if ($salt == null) {
            $salt = $this->_salt;
        }
        $hash = hash($this->_hash_type, $login . $password . $salt);
        if ($hash == $checked_hash) {
            return true;
        }
        return false;
    }

    /**
     *      Auth user
     * @param object $user - user information
     * @param boolean $remember - remember user ?
     */
    public function auth($user, $remember = false)
    {
        if (!$user) {
            return false;
        }
        $_SESSION[$this->_session] = $user->id;
        $json = json_encode([
            'remember' => (int)$remember,
            'exit' => 0,
            'id' => $user->id,
        ]);
        Cookie::set($this->_session, base64_encode($json), 60 * 60 * 24 * 7);
        return true;
    }

    /**
     *      Logout from user private panel
     */
    public function logout()
    {
        if (!isset($_SESSION[$this->_session])) {
            return false;
        }
        unset($_SESSION[$this->_session]);
        $cookie = Cookie::getArray($this->_session);
        Cookie::setArray($this->_session, [
            'remember' => (int)$cookie['remember'],
            'exit' => 1,
            'id' => $cookie['id'],
        ], 60 * 60 * 24 * 7);
        return true;
    }

    /**
     *      Check if user want to remember his password
     *      If true - auth him
     */
    public function is_remember()
    {
        if (User::info()) {
            return false;
        }
        if (!isset($_COOKIE[$this->_session])) {
            return false;
        }
        $cookie = Cookie::getArray($this->_session);
        if (!isset($cookie['remember']) || (int)$cookie['remember'] == 0) {
            return false;
        }
        if (!isset($cookie['id']) || (int)$cookie['id'] == 0) {
            return false;
        }
        if (isset($cookie['exit']) && (int)$cookie['exit'] == 1) {
            return false;
        }
        if (!isset($cookie['exit'])) {
            Cookie::set($this->_session, [
                'remember' => (int)$cookie['remember'],
                'exit' => 0,
                'id' => $cookie['id'],
            ], 60 * 60 * 24 * 7);
        }
        $user = DB::select($this->_tbl . '.*', [$this->_tbl_roles . '.alias', 'role'])
            ->from($this->_tbl)
            ->join($this->_tbl_roles)->on($this->_tbl . '.role_id', '=', $this->_tbl_roles . '.id')
            ->where($this->_tbl . '.status', '=', 1)
            ->where($this->_tbl . '.id', '=', $cookie['id']);
        if (APPLICATION == 'backend') {
            $user->where($this->_tbl_roles . '.alias', '!=', 'user');
        } else {
            $user->where($this->_tbl_roles . '.alias', '=', 'user');
        }
        $user = $user->find();
        if (!$user) {
            return false;
        }
        if ($this->auth($user, $cookie['remember'])) {
            HTTP::redirect(Arr::get($_SERVER, 'REQUEST_URI'));
        }
        return false;
    }

    /**
     *      User registration
     * @param array $data - user data from POST
     */
    public function registration($data = [])
    {
        $data['hash'] = $this->hash_user($data['email'], $data['password']);
        $data['password'] = $this->hash_password($data['password']);
        return Common::factory($this->_tbl)->insert($data);
    }

}