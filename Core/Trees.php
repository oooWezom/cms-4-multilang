<?php
namespace Core;

use Core\QB\DB;

/**
 * Class Trees
 * @package Core
 *
 * Use this model for building a trees in your project
 */
class Trees
{

    static $_instance;

    public $table = 'tree_nested';
    public $table_simple = 'tree_simple';
    public $fields = [
        'id', 'left', 'right', 'level', 'user_id',
    ];

    function __construct()
    {
    }

    function __destruct()
    {
    }

    static function instance()
    {
        if (self::$_instance == null) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }


    /**
     *      Достаем все дерево целиком
     */
    public function get_full_tree()
    {
        return DB::select($this->table . '.level', 'user_balances.*')
            ->from($this->table)
            ->join('user_balances', 'LEFT')->on('user_balances.user_id', '=', $this->table . '.user_id')
            ->where("user_balances.year", "=", date("Y", time()))
            ->where("user_balances.month", "=", date("m", time()))
            ->order_by('left', 'DESC')
            ->find_all();
    }

    /**
     *      Подчиненные узлы.
     *      Функция достаёт ветку дерева с подчинёнными узлами. Узлы достаются из дерева nested
     *      нучжно использовать для построения графов деревьев.
     *
     * @param $user_id - ID узла для которого мы достаем ветку
     * @param $include - если false, то не включаем узел $user_id
     *                        если true, то включаем узел $user_id
     * @param $separated - если true, то дерево достанет без отделившихся веток
     *                          если false, достанет полную ветку дерево не смотря на отделившиеся ветви
     * @param $year - год по которому джоинится бананс пользователя, по умолчанию текущий
     * @param $month - месяц по которому джоинится баланс пользователя, по умолчанию текущий
     *
     *
     */
    public function get_children_stem($user_id, $include = false, $separated = false, $year = null, $month = null)
    {
        if (!$year) {
            $year = date("Y", time());
        }
        if (!$month) {
            $month = date("m", time());
        }
        $month = (int)$month;
        $res = DB::select('left', 'right')->from($this->table)->where('user_id', '=', $user_id)->find();
        if (!$res) {
            return [];
        }
        if ($include) {
            $left = '>=';
            $right = '<=';
        } else {
            $left = '>';
            $right = '<';
        }

        $result = DB::select($this->table . '.level', 'user_balances.*')
            ->from($this->table)
            ->join('user_balances', 'LEFT')->on('user_balances.user_id', '=', $this->table . '.user_id');

        if ($separated) {
            $result = $result->join($this->table_simple)->on($this->table_simple . ".parent_id", "=", DB::expr($user_id))->on($this->table_simple . ".user_id", "=", $this->table . ".user_id");
        }

        $result = $result->where($this->table . '.left', $left, $res->left)
            ->where($this->table . '.right', $right, $res->right)
            ->where("user_balances.year", "=", $year)
            ->where("user_balances.month", "=", $month)
            ->order_by('left')
            ->execute()->as_array();

        if ($include && $separated) {
            $inc = DB::select(
                $this->table . ".level",
                "user_balances.*"
            )
                ->from($this->table)
                ->join("user_balances", "LEFT")
                ->on('user_balances.user_id', '=', $this->table . '.user_id')
                ->where("user_balances.year", "=", $year)
                ->where("user_balances.month", "=", $month)
                ->where($this->table . ".user_id", "=", $user_id)
                ->execute()->as_array();

            array_unshift($result, $inc[0]);
        }

        return $result;
    }


    public function get_statistics($user_id, $year = null, $month = null, $agreement_number = null)
    {
        $res = DB::select('left', 'right')->from($this->table)->where('user_id', '=', $user_id)->find();
        if (!$res) {
            return [];
        }

        if (!$year) {
            $year = date('Y');
        }
        if (!$month) {
            $month = date('n');
        }

        $result = DB::select(
            $this->table . '.level',
            'user_balances.*',
            'users.name',
            'users.last_name',
            'users.middle_name',
            ['users.created_at', 'registered'],
            'users.email',
            'users.phone',
            'users.last_login',
            'users.mobile',
            'users.agreement_number',
            'users.partner_id',
            'users.file_agreement',
            'users.expire_date',
            'users.image',
            'users.fax',
            ['partners.name', 'partner_name'],
            ['partners.last_name', 'partner_last_name'],
            ['partners.middle_name', 'partner_middle_name']
        )
            ->from($this->table)
            ->join('user_balances', 'LEFT')->on('user_balances.user_id', '=', $this->table . '.user_id')
            ->join('users')->on('users.id', '=', 'user_balances.user_id')
            ->join(['users', 'partners'])->on('users.partner_id', '=', 'partners.id')
            ->join($this->table_simple)->on($this->table_simple . ".parent_id", "=", DB::expr($user_id))->on($this->table_simple . ".user_id", "=", $this->table . ".user_id")
            ->where($this->table . '.left', '>', $res->left)
            ->where($this->table . '.right', '<', $res->right)
            ->where("user_balances.year", "=", $year)
            ->where("user_balances.month", "=", $month);
        if ($agreement_number) {
            $result->where('users.agreement_number', 'LIKE', '%' . $agreement_number . '%');
        }
        return $result->order_by('left')->execute()->as_array();
    }


    public function get_kids_personal_balances($user_id, $year, $month)
    {
        $res = DB::select('left', 'right')->from($this->table)->where('user_id', '=', $user_id)->find();
        if (!$res) {
            return [];
        }
        $result = DB::select(
            'user_balances.personal_balance',
            'user_balances.group_balance'
        )
            ->from($this->table)
            ->join('user_balances', 'LEFT')->on('user_balances.user_id', '=', $this->table . '.user_id')
            ->join($this->table_simple)->on($this->table_simple . ".parent_id", "=", DB::expr($user_id))->on($this->table_simple . ".user_id", "=", $this->table . ".user_id")
            ->where($this->table . '.left', '>', $res->left)
            ->where($this->table . '.right', '<', $res->right)
            ->where("user_balances.year", "=", $year)
            ->where("user_balances.month", "=", $month);
        return $result->order_by('left')->execute()->as_array();
    }


    public static function getMyStatistics($user_id, $year, $month)
    {
        $bal = DB::select()
            ->from('user_balances')
            ->where('user_id', '=', $user_id)
            ->where('year', '=', $year)
            ->where('month', '=', $month)
            ->execute()->as_array();
        if (!sizeof($bal)) {
            return false;
        }
        $bal = $bal[0];
        if (!sizeof($bal)) {
            return false;
        }

        $active_referrals = 0;
        $sleep_referrals = 0;
        $referrals = 0;
        $tree = Trees::instance()->get_statistics($user_id, $year, $month);
        foreach ($tree as $kid) {
            if ($kid['personal_balance']) {
                $active_referrals++;
            }
            if (!$kid['group_balance']) {
                $sleep_referrals++;
            }
            $referrals++;
        }

        $orders_amount = DB::select([DB::expr('SUM(orders_items.cost * orders_items.count)'), 'amount'])
            ->from('orders')
            ->join('orders_items')->on('orders.id', '=', 'orders_items.order_id')
            ->where('orders.user_id', '=', $user_id)
            ->where('orders.status', '=', 2)
            ->where(DB::expr('FROM_UNIXTIME(orders.done, "%Y-%c")'), '=', $year . '-' . $month)
            ->find();
        $orders_amount = $orders_amount ? (int)$orders_amount->amount : 0;

        $current_bal = [];
        $current_bal['credits'] = $bal['credits'];
        $current_bal['count_referrals'] = $referrals;
        $current_bal['active_referrals'] = $active_referrals;
        $current_bal['orders_amount'] = $orders_amount;
        $current_bal['personal_balance'] = $bal['personal_balance'];
        $current_bal['group_balance'] = $bal['group_balance'];
        $current_bal['count_new_referrals'] = $bal['count_new_referrals'];
        $current_bal['active_groups'] = $bal['count_separated_groups'];
        $current_bal['sleep_referrals'] = $sleep_referrals;

        return $current_bal;
    }


    /**
     *      Ветка из отцовских узлов
     * @param $user_id - ID узла для которого мы достаем ветку
     * @param $include - если false, то не включаем узел $user_id
     *                        если true, то включаем узел $user_id
     * @param $separated - если false, достаёт всю ветку до наипервого родителя
     *                          если true, достанет ветку, в которой верхний родитель - верхнее отделившееся звено
     * @param $year - год по которому джоинится бананс пользователя, по умолчанию текущий
     * @param $month - месяц по которому джоинится баланс пользователя, по умолчанию текущий
     *
     */
    public function get_parent_stem($user_id, $include = false, $separated = false, $year = null, $month = null)
    {
        if (!$year) {
            $year = date("Y", time());
        }
        if (!$month) {
            $month = date("m", time());
        }
        $month = (int)$month;
        $res = DB::select('left', 'right')->from($this->table)->where('user_id', '=', $user_id)->find();
        if (!$res) {
            return [];
        }
        if ($include) {
            $left = '<=';
            $right = '>=';
        } else {
            $left = '<';
            $right = '>';
        }
        $result = DB::select($this->table . '.level', 'user_balances.*')
            ->from($this->table)
            ->join('user_balances', 'LEFT')->on('user_balances.user_id', '=', $this->table . '.user_id');
        if ($separated) {
            $result = $result->join($this->table_simple)
                ->on($this->table_simple . ".parent_id", "=", $this->table . ".user_id")
                ->on($this->table_simple . ".user_id", "=", DB::expr($user_id));
        }
        $result = $result->where($this->table . '.left', $left, $res->left)
            ->where($this->table . '.right', $right, $res->right)
            ->where("user_balances.year", "=", $year)
            ->where("user_balances.month", "=", $month)
            ->order_by('left', "DESC")
            ->execute()->as_array();

        if ($include && $separated) {
            $inc = DB::select(
                $this->table . ".level",
                "user_balances.*"
            )
                ->from($this->table)
                ->join("user_balances", "LEFT")
                ->on('user_balances.user_id', '=', $this->table . '.user_id')
                ->where("user_balances.year", "=", $year)
                ->where("user_balances.month", "=", $month)
                ->where($this->table . ".user_id", "=", $user_id)
                ->execute()->as_array();

            array_unshift($result, $inc[0]);
        }

        return $result;
    }

    /**
     *      Достаем массив ID`шников из отцов
     * @param $user_id - ID узла для которого мы достаем ветку
     * @param $include - если false, то не включаем узел $user_id
     *                        если true, то включаем узел $user_id
     * @param $separated - если false, достаёт всю ветку до наипервого родителя
     *                          если true, достанет ветку, в которой верхний родитель - верхнее отделившееся звено
     * @param $year - год по которому джоинится бананс пользователя, по умолчанию текущий
     * @param $month - месяц по которому джоинится баланс пользователя, по умолчанию текущий
     */
    public function get_parent_stem_ids($user_id, $include = false, $separated = false, $year = null, $month = null)
    {
        if (!$year) {
            $year = date("Y", time());
        }
        if (!$month) {
            $month = date("m", time());
        }
        $month = (int)$month;
        $res = $this->get_parent_stem($user_id, $include, $separated, $year, $month);
        if (!$res) {
            return [];
        }
        $arr = [];
        foreach ($res AS $obj) {
            $arr[] = $obj['user_id'];
        }
        return $arr;
    }

    /**
     *      Подчиненные узлы.
     *      Функция достаёт ветку дерева с подчинёнными узлами. Узлы достаются из дерева nested
     *      нучжно использовать для построения графов деревьев.
     *
     * @param $user_id - ID узла для которого мы достаем ветку
     * @param $include - если false, то не включаем узел $user_id
     *                        если true, то включаем узел $user_id
     * @param $separated - если true, то дерево достанет без отделившихся веток
     *                          если false, достанет полную ветку дерево не смотря на отделившиеся ветви
     * @param $year - год по которому джоинится бананс пользователя, по умолчанию текущий
     * @param $month - месяц по которому джоинится баланс пользователя, по умолчанию текущий
     *
     */
    public function get_children_stem_ids($user_id, $include = false, $separated = false, $year = null, $month = null)
    {
        if (!$year) {
            $year = date("Y", time());
        }
        if (!$month) {
            $month = date("m", time());
        }
        $month = (int)$month;
        $res = $this->get_children_stem($user_id, $include, $separated, $year, $month);
        if (!$res) {
            return [];
        }
        $arr = [];

        foreach ($res AS $obj) {
            $arr[] = $obj['user_id'];
        }

        return $arr;
    }


    /**
     *      Подчиненные узлы
     * @param $user_id - ID узла для которого мы достаем ветку
     */
    public function get_full_stem($user_id)
    {
        $res = DB::select('left', 'right')->from($this->table)->where('user_id', '=', $user_id)->find();
        if (!$res) {
            return [];
        }
        return DB::select($this->table . '.level', 'users.*')
            ->from($this->table)
            ->join('users', 'LEFT')->on('users.id', '=', $this->table . '.user_id')
            ->where($this->table . '.left', '<', $res->right)
            ->where($this->table . '.right', '>', $res->left)
            ->order_by('left')
            ->find_all();
    }


    /**
     *      Добавляем узел на самый верхний уровень дерева (отец есть, а детей нет)
     * @param $user_id - ID нового юзера
     * @param $parent_id - ID бати
     *
     *      МЕТОД РАБОТАЕТ
     */
    public function add($user_id, $parent_id, $status = 1)
    {
        $res = DB::select('id')->from($this->table)->where('user_id', '=', $user_id)->find();
        if ($res) {
            return false;
        }
        $parent = DB::select('right', 'level')->from($this->table)->where('user_id', '=', $parent_id)->find();
        if (!$parent) {
            return false;
        }
        $res = DB::update($this->table)
            ->set([
                $this->table . '.right' => DB::expr($this->table . '.right + 2'),
                $this->table . '.left' => DB::expr('IF(' . $this->table . '.left > ' . $parent->right . ', ' . $this->table . '.left + 2, ' . $this->table . '.left)'),
            ])
            ->where($this->table . '.right', '>=', $parent->right)
            ->execute();

        if (!$res) {
            return false;
        }
        $tree_nested = (boolean)Common::factory($this->table)->insert([
            'left' => $parent->right,
            'right' => $parent->right + 1,
            'level' => $parent->level + 1,
            'user_id' => $user_id,
            'status' => $status,
        ]);

        if (!$tree_nested) {
            return false;
        }

        //Выбираем всех родителей которым нужно присвоить нового ребёнка в tree_simple
        //выбираем отдельнвм запросом не используя дерево, так как данные о новом узле ещё не занесены

        $parents = DB::select($this->table_simple . ".parent_id")
            ->from($this->table_simple)
            ->where($this->table_simple . ".user_id", "=", $parent_id)
            ->find_all();


        $result = DB::insert($this->table_simple, [
            "parent_id",
            "user_id"
        ]);
        if (count($parents)) {
            foreach ($parents as $obj) {
                $result = $result->values([
                    $obj->parent_id,
                    $user_id
                ]);
            }
            $result = $result->values([
                $parent_id,
                $user_id
            ]);
        } else {
            $result = $result->values([
                $parent_id,
                $user_id
            ]);
        }
        $result = $result->execute();

        if (!$result) {
            $this->delete($user_id);
            return false;
        }

        //добавляем новую запись в балансы пользователей
        $user_balance = DB::insert("user_balances", [
            "user_id",
            "year",
            "month",
            "personal_balance",
            "group_balance",
            "credits"
        ])
            ->values([
                $user_id,
                date("Y", time()),
                date("m", time()),
                0,
                0,
                0
            ])
            ->execute();

        //Ставим статус партёна пользователю
        $upd = DB::update("users")
            ->set([
                "partner" => 1,
                "partner_id" => $parent_id
            ])
            ->where("id", "=", $user_id)
            ->execute();

        return true;
    }


    /**
     *      Удаляем узел
     * @param $user_id - ID удаляемого юзера
     *
     *    РАБОЧИЙ МЕТОД
     */
    public function delete($user_id)
    {
        //получаем пользователя
        $user = DB::select()
            ->from("users")
            ->where("id", "=", $user_id)
            ->where("partner", "=", 1)
            ->find();

        if (!$user) {
            return false;
        }

        //Удаление пользователя с нестед дерева
        $parent = DB::select('right', 'left', 'level')->from($this->table)->where('user_id', '=', $user_id)->find();
        if (!$parent) {
            return false;
        }
        DB::update($this->table)
            ->set([
                'right' => DB::expr('`right`-1'),
                'left' => DB::expr('`left` - 1'),
                'level' => DB::expr('`level` - 1'),
            ])
            ->where('left', '>', $parent->left)
            ->where('right', '<', $parent->right)
            ->execute();
        DB::update($this->table)
            ->set([
                'right' => DB::expr('`right` - 2'),
            ])
            ->where('left', '<', $parent->left)
            ->where('right', '>', $parent->right)
            ->execute();
        DB::update($this->table)
            ->set([
                'right' => DB::expr('`right` - 2'),
                'left' => DB::expr('`left` - 2'),
            ])
            ->where('left', '>', $parent->left)
            ->where('right', '<', $parent->right)
            ->execute();
        $r = DB::delete($this->table)->where('user_id', '=', $user_id)->execute();

        //удаление пользователя с обычного дерева
        $r = DB::delete($this->table_simple)
            ->where("parent_id", "=", $user_id)
            ->or_where("user_id", "=", $user_id)
            ->execute();

        //удаление пользователя из истории балансов реферала
        $r = DB::delete("user_balances")
            ->where("user_id", "=", $user_id)
            ->execute();

        //Обновление непосредственных детей удаляемого узла
        $r = DB::update("users")
            ->set([
                "partner_id" => $user->partner_id
            ])
            ->where("partner_id", "=", $user_id)
            ->execute();

        //Обновление данного пользователя указывая статс НЕ партнёра
        $r = DB::update("users")
            ->set([
                "partner" => 0
            ])
            ->where("id", "=", $user_id)
            ->execute();

        return true;

    }


    /**
     *      Достаем уровень, левый и правый ключи для юзера
     * @param $user_id - ID юзера для поиска строки
     */
    public function get_node_information($user_id)
    {
        return DB::select('level', 'right', 'left')->from($this->table)->where('user_id', '=', $user_id)->find();
    }

    /**
     *
     * Метод достаёт количство детей указанного пользователя
     *
     * @param $user_id - узел для которого подсчитывается количество рефералов
     */
    public function getCountReferals($user_id)
    {
        $count = DB::select(
            [DB::expr("COUNT(id)"), "count"]
        )
            ->from($this->table_simple)
            ->where("parent_id", "=", $user_id)
            ->count_all();

        return $count;
    }

    /**
     * Метод обновления груповых балансов для родительской ветки.
     *
     * @param $user_id - id пользователя от которо начинается добавление обновление балансов дерева
     * @param $balance - количество БАЛОВ групового баланса
     * @param $order_id - номер заказа на основании которого производится оюновление балансов
     * @param $update_parents - указатель обновлять ли балансы родителей
     *                          false - не обновлять баласны родителей данного пользователя
     *                          true - обновлять балансы родителей данного пользователя
     */
    public function updateBalance($user_id, $balance, $order_id, $update_parents = false)
    {

        //находим пользователя от которо начинается пересчёт
        $user = DB::select(
            "users.*",
            "user_balances.group_balance",
            "user_balances.personal_balance"
        )
            ->from("users")
            ->join("user_balances", "LEFT")
            ->on("user_balances.user_id", "=", "users.id")
            ->where("users.partner", "=", 1)
            ->where("users.id", "=", $user_id)
            ->where("user_balances.year", "=", date("Y", time()))
            ->where("user_balances.month", "=", date("m", time()))
            ->find();

        if (!$user) {
            return false;
        }


        $array = [
            "personal_balance" => DB::expr("personal_balance + " . $balance),
            "group_balance" => DB::expr("group_balance + " . $balance)
        ];


        //обновляем баланс данного пользователя
        DB::update("user_balances")
            ->set($array)
            ->where("user_id", "=", $user_id)
            ->where("year", "=", date("Y", time()))
            ->where("month", "=", date("m", time()))
            ->execute();

        //записать в историю балансов добавления в баланс
        DB::insert("history_balances", [
            "add_type",
            "order_id",
            "points",
            "user_id",
            "created_at"
        ])->values([
            1,
            $order_id,
            $balance,
            $user_id,
            time()
        ])->values([
            2,
            $order_id,
            $balance,
            $user_id,
            time()
        ])->execute();


        //проверяю статусы пользователя после обновления баланса
        $old_balance = $user->group_balance; //баланс, который был у пользователя до проведения операции
        $new_balance = $user->group_balance + $balance; // новый баланс пользоваетя
        $old_status = null; //предопределение переменной статуса, который был у пользователя до проведения операции
        $new_status = null; //предопределение переменной статуса, который получил пользователь после проведения оперции
        foreach (Statuses::$statuses as $j) {
            if ($old_balance >= $j->from && $old_balance <= $j->to) {
                $old_status = $j; //выставление статуса пользователя, до операции
            }
            if ($new_balance >= $j->from && $new_balance <= $j->to) {
                $new_status = $j; //выставления статуса пользователя, что стал после выполнения операции
            }
        }

        //проверка статусов пользователя

        if ($old_status->id != $new_status->id) {
            //получаем шаблон письма
            $mail = DB::select()
                ->from("mail_templates")
                ->where("status", "=", 1)
                ->where("id", "=", 22)
                ->find();
            if ($mail) {
                //заполняем переменные
                $from = [
                    '{{site}}',
                    '{{ip}}',
                    '{{date}}',
                    '{{status_name}}'
                ];

                $to = [
                    Arr::get($_SERVER, 'HTTP_HOST'),
                    System::getRealIP(),
                    date('d.m.Y'),
                    $new_status->name . " (" . $new_status->percent . "%)"

                ];
                $subject = str_replace($from, $to, $mail->subject);
                $text = str_replace($from, $to, $mail->text);

                //добавляем запись в крон для отправки письма
                Common::factory("cron")->insert([
                    "email" => $user->email,
                    "subject" => $subject,
                    "text" => $text
                ]);

                //TODO отправить системное сообщение
            }
        }

        //проверка, не отделяется ли группа

        //если группа не отделяется
        if ($new_status->id != 4) {

            //проверяем, нужно ли пересчитывать родителей данного партнёра
            if ($update_parents) {
                $this->updateParentsBalances($user_id, $balance, $order_id);
            }

        } //если узел отделяется
        elseif ($new_status->id == 4 && $old_status->id != 4) {
            $r = $this->separateGroup($user_id, $order_id, $user->group_balance);
        }

        //если узел возвращается обратно
        if ($old_status->id == 4 && $new_status->id != 4) {
            $this->joinGroup($user_id, $order_id);
        }


        return true;


    }

    /**
     * Функция обновления груповых балансов родителей указанного ребёнка
     *
     * @param $user_id - узел, чьих родителей нужно обновить
     * @param $balance - количество балов добавляемое/уменьшаемое в групповом балансе пользователей
     * @param $order_id - заказ, на основании которого происходит операция обновления
     */
    public function updateParentsBalances($user_id, $balance, $order_id)
    {
        //получаем всех родителей данного ребёнка
        $parents = $this->get_parent_stem($user_id, false, true);

        //проверяем, не попали ли на обновление родителя администратора
        if (count($parents)) {
            //бежим по каждому из родителей снизу вверх и обновляем баланс родителя
            foreach ($parents as $obj) {
                $array = [
                    "group_balance" => DB::expr("group_balance + " . $balance)
                ];


                //обновляем баланс пользователя
                DB::update("user_balances")
                    ->set($array)
                    ->where("user_id", "=", $obj['user_id'])
                    ->where("year", "=", date("Y", time()))
                    ->where("month", "=", date("m", time()))
                    ->execute();

                //записать в историю балансов добавления в баланс
                DB::insert("history_balances", [
                    "add_type",
                    "order_id",
                    "points",
                    "user_id",
                    "created_at"
                ])->values([
                    2,
                    $order_id,
                    $balance,
                    $obj['user_id'],
                    time()
                ])->execute();


                //проверяю статусы пользователя после обновления баланса
                $old_balance = $obj['group_balance']; //баланс, который был у пользователя до проведения операции
                $new_balance = $obj['group_balance'] + $balance; // новый баданс пользоваетя
                $old_status = null; //предопределение переменной статуса, который был у пользователя до проведения операции
                $new_status = null; //предопределение переменной статуса, который получил пользователь после проведения оперции
                foreach (Statuses::$statuses as $j) {
                    if ($old_balance >= $j->from && $old_balance <= $j->to) {
                        $old_status = $j; //выставление статуса пользователя, до операции
                    }
                    if ($new_balance >= $j->from && $new_balance <= $j->to) {
                        $new_status = $j; //выставления статуса пользователя, что стал после выполнения операции
                    }
                }

                //проверка статусов пользователя

                if ($old_status->id != $new_status->id) {

                    //получаем емейл пользователя, которому нужно отправить сообщение
                    $user = DB::select("email")
                        ->from("users")
                        ->where("id", "=", $obj['user_id'])
                        ->find();

                    //получаем шаблон письма
                    $mail = DB::select()
                        ->from("mail_templates")
                        ->where("status", "=", 1)
                        ->where("id", "=", 22)
                        ->find();
                    if ($mail) {
                        //заполняем переменные
                        $from = [
                            '{{site}}',
                            '{{ip}}',
                            '{{date}}',
                            '{{status_name}}'
                        ];

                        $to = [
                            Arr::get($_SERVER, 'HTTP_HOST'),
                            System::getRealIP(),
                            date('d.m.Y'),
                            $new_status->name . " (" . $new_status->percent . "%)"

                        ];
                        $subject = str_replace($from, $to, $mail->subject);
                        $text = str_replace($from, $to, $mail->text);

                        //добавляем запись в крон для отправки письма
                        Common::factory("cron")->insert([
                            "email" => $user->email,
                            "subject" => $subject,
                            "text" => $text
                        ]);

                        //TODO отправить системное сообщение
                    }
                }

                //проверка, не отделяется ли группа
                if ($new_status->id == 4 && $old_status->id != 4) {
                    $this->separateGroup($obj['user_id'], $order_id, $obj['group_balance']);
                    break;
                }

                if ($old_status->id == 4 && $new_status->id != 4) {
                    $this->joinGroup($obj['user_id'], $order_id);
                }
            }
        }
    }


    /**
     *  Функция отделяющая группу
     *
     * @param $user_id - верхний родитель группы, которая отделяется
     * @return bool - возвращает прошла ли операция успешно
     * @param - баланс группы которая отсоединяется (баланс, который был до того, как группа получила статус платинового)
     */
    public function separateGroup($user_id, $order_id, $group_balance)
    {
        //получаем пользователя который отделаяется
        $user = DB::select(
            "users.*",
            "user_balances.group_balance"
        )
            ->join("user_balances")
            ->on("user_balances.user_id", "=", "users.id")
            ->from("users")
            ->where("users.partner", "=", 1)
            ->where("users.id", "=", $user_id)
            ->where("user_balances.year", "=", date("Y", time()))
            ->where("user_balances.month", "=", date("m", time()))
            ->find();


        if (!$user) {
            return false;
        }

        //получаем партнёра от которого отделяется пользователь
        $partner = DB::select()
            ->from("users")
            ->where("partner", "=", 1)
            ->where("id", "=", $user->partner_id)
            ->find();
        if (!$partner) {
            return false;
        }

        //получаем айдишники родителей данного узла
        $parents_ids = $this->get_parent_stem_ids($user_id, false, true);
        //получаем айдишники детей данного узла
        $childs_ids = $this->get_children_stem_ids($user_id, true, true);
        //добавляем к массиву детей ещё ид данного пользователя, так как метод не может выбрать детей без отделившихся групп вмесмте с данным узлом
        //$childs_ids[] = $user_id;

        //проверка, не происходит ли отделение главного администратора
        if (count($parents_ids)) {

            //подсчитываем значение для уменьшения балансов
            $update_parent_balances_value = (int)("-" . $group_balance);

            //устанавливаем новые балансы для родителей отделившейся группы
            $this->updateParentsBalances($user_id, $update_parent_balances_value, $order_id);

            //удаляем записи из обычного дерева, где ребёнком является данный узел (для того что бы потом не проходил джоин)
            DB::delete($this->table_simple)
                ->where("parent_id", "IN", $parents_ids)
                ->where("user_id", "IN", $childs_ids)
                ->execute();

            //добавляем запись в таблицу отделившихся групп о новом отединении
            Common::factory("separated_groups")->insert([
                "parent_id" => $user->partner_id,
                "user_id" => $user_id
            ]);


            //отправляем емейл пользователю, чья группа отделилась
            $mail = DB::select()
                ->from("mail_templates")
                ->where("status", "=", 1)
                ->where("id", "=", 23)
                ->find();
            if ($mail) {
                //заполняем переменные для отправки
                $from = [
                    '{{site}}',
                    '{{ip}}',
                    '{{date}}',
                    '{{user_name}}',
                    '{{agreement_number}}',
                ];

                $to = [
                    Arr::get($_SERVER, 'HTTP_HOST'),
                    System::getRealIP(),
                    date('d.m.Y'),
                    $user->name . " " . $user->last_name,
                    $user->agreement_number

                ];
                $subject = str_replace($from, $to, $mail->subject);
                $text = str_replace($from, $to, $mail->text);

                //добавляем запись в крон для отправки письма
                Common::factory("cron")->insert([
                    "email" => $partner->email,
                    "subject" => $subject,
                    "text" => $text
                ]);
            }

        }

        //уменьшаем родителю количество отделившихся групп
        DB::update("user_balances")
            ->set([
                "count_separated_groups" => DB::expr("count_separated_groups + 1")
            ])
            ->where("user_id", "=", $partner->id)
            ->where("year", "=", date("Y", time()))
            ->where("month", "=", date("m", time()))
            ->execute();

    }

    /**
     *  Функция, которая возвращает в зад отделившуюся в группу, в результате возврата заказа или в результате
     *  отсоединения низшей группу, которая позволяла набирать данный статус
     *
     * @param $user_id - наивысший узел возвращаемой группы
     * @param $order_id - заказ на основании которого происходит присоединение группы на место
     */
    public function joinGroup($user_id, $order_id)
    {

        //получаем наивысшего родителя отделившейся группы
        $parent = DB::select()
            ->from("separated_groups")
            ->where("user_id", "=", $user_id)
            ->find();

        if (!$parent) {
            return false;
        }

        //получаем всех родителей данного родителя, до наивысшей отделившейся ветви
        $parents_ids = $this->get_parent_stem_ids($parent->parent_id, true, true);

        //получаем всех детей данной отделившейся группы, которую присоединяем обратно включая верхний узел группы
        $childs_ids = $this->get_children_stem_ids($user_id, true, true);

        //удаляем информацию об отделившейся группе из таблицы separated_groups
        DB::delete("separated_groups")
            ->where("id", "=", $parent->id)
            ->execute();

        //добавляем в таблицу обычного дерева недостающие записи
        $r = DB::insert($this->table_simple, [
            "parent_id",
            "user_id"
        ]);

        foreach ($parents_ids as $obj) {
            foreach ($childs_ids as $j) {
                $r = $r->values([
                    $obj,
                    $j
                ]);
            }
        }
        $r = $r->execute();

        //получаем груповой баланс присоединяющейся группы
        $group = DB::select()
            ->from("user_balances")
            ->where("user_id", "=", $user_id)
            ->where("year", "=", date("Y", time()))
            ->where("month", "=", date("m", time()))
            ->find();

        //обновляем балансы всех родителей, к которым присоединяется данная группа
        $this->updateParentsBalances($user_id, $group->group_balance, $order_id);

        //уменьшаем родителю количество отделившихся групп
        DB::update("user_balances")
            ->set([
                "count_separated_groups" => DB::expr("count_separated_groups - 1")
            ])
            ->where("user_id", "=", $parent->parent_id)
            ->where("year", "=", date("Y", time()))
            ->where("month", "=", date("m", time()))
            ->execute();

        return true;

    }

    /**
     * Функция, которую необходимо вызвать 1-го числа нового месяца,
     * для возвращения дерева в первозданный вид
     *
     * @param $year - год на который будет обпираться функция при очистке дерева
     * @param $month - месяц на который будеит обпираться функция, при очистке дерева
     *
     *  Данный два пареметра неоходимы, что бы верно выбирались родители и дети при возвращении всех
     *  отделившихся структур
     *
     */
    public function cleanTree($year, $month)
    {
        //в первую очередь возвращаем на место все отсоединившиеся группы
        $this->returnAllSeparatedGroups($year, $month);

        //далее запускаем функцию, которая создаст новые балансы пользователя для текущего месяца
        $this->createNewUserBalances();
    }

    /**
     * Функция возвращает все отсоединившиеся за месяц группы на своё место без пересчёта
     * балансов родителей
     *
     * @param $year - год на который будет обпираться функция при очистке дерева
     * @param $month - месяц на который будеит обпираться функция, при очистке дерева
     */
    public function returnAllSeparatedGroups($year, $month)
    {
        //получаем список отсоединившихся групп
        $separated_groups = DB::select()
            ->from("separated_groups")
            ->find_all();

        //бежим циклом по данным групам и возвращаем их обратно
        foreach ($separated_groups as $obj) {
            //получаем всех родителей данного родителя, до наивысшей отделившейся ветви
            $parents_ids = $this->get_parent_stem_ids($obj->parent_id, true, true, $year, $month);

            //получаем всех детей данной отделившейся группы, которую присоединяем обратно включая верхний узел группы
            $childs_ids = $this->get_children_stem_ids($obj->user_id, true, true, $year, $month);

            //добавляем в таблицу обычного дерева недостающие записи
            $r = DB::insert($this->table_simple, [
                "parent_id",
                "user_id"
            ]);

            foreach ($parents_ids as $obj) {
                foreach ($childs_ids as $j) {
                    $r = $r->values([
                        $obj,
                        $j
                    ]);
                }
            }
            $r = $r->execute();
        }

        //очищаем таблицу с отсоединившимися групами
        DB::delete("separated_groups")
            ->where("id", ">", 0)
            ->execute();
    }

    /**
     * Функция, которая создаст новые записи в таблице балансов пользователей
     * в соответствии с текущим месяцем
     */
    public function createNewUserBalances()
    {
        //получаем всех пользователей, которые являются партнёрами
        $users = DB::select()
            ->from("users")
            ->where("partner", "=", 1)
            ->find_all();

        $i = 1; //счётчик количества добавляемых записей
        $r = DB::insert("user_balances", [
            "user_id",
            "year",
            "month",
            "personal_balance",
            "group_balance",
            "credits",
            "count_separated_groups"
        ]); // предопределение запроса

        $u = false; //указатель, нужно ли после цикла запускать сформированный зарос, или последний сформированный
        // запрос был запущен внутри цыкла (true - нужно запустить запрос, false - запрос запускать не нужно)

        //бежим по всем пользователям формируя новые запросы
        foreach ($users as $obj) {
            $u = true;
            $r = $r->values([
                $obj->id,
                date("Y", time()),
                date("m", time()),
                0,
                0,
                0,
                0
            ]);
            $i++; // добавляем количество запросов
            //проверка, для того, что бы запрос не был слишком большим
            if ($i == 500) {
                //запусаем запрос
                $r = $r->execute();

                //обнуляем счётчик количества
                $i = 1;

                //заново создаём предопределение запроса
                $r = DB::insert("user_balances", [
                    "user_id",
                    "year",
                    "month",
                    "personal_balance",
                    "group_balance",
                    "credits",
                    "count_separated_groups"
                ]);

                //ставим указатель если это последняя итерация цикла
                $u = false;
            }
        }

        //запускаем запрос после цикла, если нужно
        if ($u) {
            $r = $r->execute();
        }
    }

    /**
     * Функция обновления балансов личных кредитов, которые
     * в дальнейшем переводятся в деньги
     *
     * @param $year - год за который будет начислены кредиты
     * @param $month - месяц за который происходит начисление
     */
    public function updateCredits($year, $month)
    {
        $month = (int)$month;
        //получаем всех пользователей которые участвуют в реферальной системе
        $users = DB::select(
            "users.*",
            "user_balances.group_balance",
            "user_balances.personal_balance"
        )
            ->from("users")
            ->join("user_balances", "LEFT")
            ->on("user_balances.user_id", "=", "users.id")
            ->where("partner", "=", 1)
            ->where("user_balances.year", "=", $year)
            ->where("user_balances.month", "=", $month)
            ->find_all();

        //бежим по всем пользователям
        foreach ($users as $obj) {
            //начисляем доходы по личным баллам за текущий месяц
            $this->accuralForPersonalBalance($obj, $year, $month);
            //начисляем бонусы за стабильность, если таковые имеются
            $this->accuralStabilityBonus($obj, $year, $month);
            //начисляем кредиты с груповых балансов рефералов первого уровня
            $this->accuralForReferalsGroupBalance($obj, $year, $month);
            //считаем количество рефералов данного пользователя, и записываем
            $this->setCountReferrals($obj->id, $year, $month);
        }

        //начисляем всем родителям, имеющим отделившиеся структуры бонусы
        //ВНИМАНИЕ начисление происходит тоько по текущему состоянию дерева!!!!
        $this->accuralForSeparatedGroups($year, $month);

        //возвращаем все отделившиеся группы на место
        //TODO НАПИСАТЬ ФУНКЦИЮ, КОТОРАЯ ВЕРНЁТ ВСЕ ОТДЕЛИВШИЕСЯ ГРУППЫ

    }

    /**
     * Функция, которая начисляет кредиты за текущий месяц по личному баллансу
     * также функция выставляет статус тсабильности данному пользователю
     *
     * @param $user - обьект пользователя полученный в результате запроса в методе updateCredits
     * @param $year - год за который нужно обновить счёт
     * @param $month - месяц за который нужно обновить счёт
     */
    public function accuralForPersonalBalance($user, $year, $month)
    {

        //получаем список причин для начислений
        $reasons = Config::get("historyCredits.reasons");

        //получаем персональный минимум, который должен набрать пользователь
        $personal_minimum = Config::get("cost.personal_minimum");

        //получаем статус данного пользователя
        $status = null;
        foreach (Statuses::$statuses as $obj) {
            if ($user->group_balance >= $obj->from && $user->group_balance <= $obj->to) {
                $status = $obj;
            }
        }

        //подсчитываем количество кредитов, которые нужно начислить
        $credits = ceil(($status->percent / 100) * $user->personal_balance);

        //указатель на прохождение обновления
        $u = false;
        //проверяем прошёл ли пользователь отметку персонального минимума
        if ($personal_minimum <= $user->personal_balance) {

            //проверяем, не составляет ли поплнение 0, что б не делать глупых действий
            if ($credits > 0) {
                //пополняем баланс кредитов пользователя, а также устанавливаем стутаус в текущем месяце
                DB::update("user_balances")
                    ->set([
                        "credits" => DB::expr("credits+" . $credits),
                        "status_at_month" => $status->id
                    ])
                    ->where("user_id", "=", $user->id)
                    ->where("year", "=", $year)
                    ->where("month", "=", $month)
                    ->execute();

                $u = true;

                //записываем в историю пополнений
                Common::factory("history_credits")->insert([
                    "user_id" => $user->id,
                    "reason" => 2,
                    "credits" => $credits,
                    "text" => $reasons[2],
                ]);
            }
        }

        //в обязательном порядке устанавливаем месячный статус пользователя
        if (!$u) {
            DB::update("user_balances")
                ->set([
                    "status_at_month" => $status->id
                ])
                ->where("user_id", "=", $user->id)
                ->where("year", "=", $year)
                ->where("month", "=", $month)
                ->execute();
        }
    }

    /**
     *
     * Функция, которая подсчитывает стабильность указаного пользователя, и начисляет бонус, если таковой нужен
     *
     * @param $user - обьект пользователя полученный в результате запроса в методе updateCredits
     * @param $year - год за который нужно обновить счёт
     * @param $month - месяц за который нужно обновить счёт
     */
    public function accuralStabilityBonus($user, $year, $month)
    {

        //получаем список причин для начислений
        $reasons = Config::get("historyCredits.reasons");

        $month = (int)$month;

        //получаем количество месяцев за котороен насчитывается бонус стабильности
        $count_month = Config::get("costs.stability_month") + 1;

        //формируем список месяцев и годов, за которые необходимо достать балансы
        $m = [];
        $y[] = $year;

        for ($i = 0; $i < $count_month; $i++) {
            //проверяем не слишком ли упал месяц, если да, то понижаем год
            if ($month == 0) {
                $month = 12;
                $y[] = $year--;
            }

            //добавляем в массив месяцев
            $m[] = $month;

            //уменьшаем месяц
            $month--;
        }

        //получаем список балансов пользователей
        $balances = DB::select()
            ->from("user_balances")
            ->where("user_id", "=", $user->id)
            ->where("year", "IN", $y)
            ->where("month", "IN", $m)
            ->order_by("id", "ASC")
            ->limit($count_month)
            ->find_all();

        //проверяем количество что б не делать глупую работы
        if (count($balances) == $count_month) {

            //предопределяем массив со статусами, в котором все количества равны 0
            $stability_count = Statuses::getStabilityCount();

            //бежим по балансам считая какой баланс сколько раз встретился
            foreach ($balances as $obj) {
                if ($obj->pay_bonus) {
                    $stability_count = Statuses::getStabilityCount();
                } else {
                    $stability_count[$obj->status_at_month] = $stability_count[$obj->status_at_month] + 1;
                }
            }

            //реверс массива количества, теперь ключём является количество раз встречаемого статуса
            $stability_count = array_flip($stability_count);

            //проверка, встречается ли какой-то из статусов n раз, n-указывается в админке
            if ($stability_count[($count_month - 1)]) {
                //записываем стабильный статус в новую переменную
                $stab_status = Statuses::$statuses[$stability_count[($count_month - 1)]];

                //проверяем, если стабильный статус не 5%
                if ($stab_status->id != 1) {

                    //проеряем если бонус за стабильность у данного статуса больше 0
                    if ($stab_status->bonus_for_stability > 0) {
                        //обновляем баланс кредитов пользователя
                        DB::update("user_balances")
                            ->set([
                                "credits" => DB::expr("credits + " . $stab_status->bonus_for_stability),
                                "pay_bonus" => 1
                            ])
                            ->where("user_id", "=", $user->id)
                            ->where("year", "=", $year)
                            ->where("month", "=", $month)
                            ->execute();


                        //записываем в историю пополнений
                        Common::factory("history_credits")->insert([
                            "user_id" => $user->id,
                            "reason" => 5,
                            "credits" => $stab_status->bonus_for_stability,
                            "text" => $reasons[5]
                        ]);
                    }
                }
            }
        }
    }

    /**
     * @param $user - обьект пользователя полученный в результате запроса в методе updateCredits,
     * начисление будет происходить от делей именно этого пользователя
     * также данный метод начисляет бонусы за новичков
     * @param $year - год за который нужно обновить счёт
     * @param $month - месяц за который нужно обновить счёт
     */
    public function accuralForReferalsGroupBalance($user, $year, $month)
    {
        //получаем список причин для начислений
        $reasons = Config::get("historyCredits.reasons");

        $month = (int)$month;

        /*получаем всех непосредственных детей указанного родителя
        поле count_balances указывает количество месяцев, которое уже существует данный ребёнок
        если 0 то родителю пологается бонус за новичка*/
        $childs = DB::select(
            "users.*",
            "user_balances.personal_balance",
            "user_balances.group_balance",
            ["user_balances.id", "balance_id"],
            [DB::expr("(SELECT COUNT(ub.id) FROM user_balances as ub WHERE ub.id < balance_id AND ub.user_id=users.id)"), "count_balances"]
        )
            ->from("users")
            ->join("user_balances")
            ->on("user_balances.user_id", "=", "users.id")
            ->join($this->table_simple)
            ->on($this->table_simple . ".user_id", "=", "users.id")
            ->where("user_balances.year", "=", $year)
            ->where("user_balances.month", "=", $month)
            ->where("users.partner_id", "=", $user->id)
            ->where("users.partner", "=", 1)
            ->where($this->table_simple . ".parent_id", "=", $user->id)
            ->find_all();

        //проверяем, существуют ли у данного пользователя дети
        if (count($childs)) {
            //расчитыаем статус указанного родителя
            $user_status = null;
            foreach (Statuses::$statuses as $obj) {
                if ($user->group_balance >= $obj->from && $user->group_balance <= $obj->to) {
                    $user_status = $obj;
                }
            }

            //бежим по кадому из детей
            foreach ($childs as $obj) {

                //узнаём новый статус ребёнка
                $child_status = null;
                foreach (Statuses::$statuses as $j) {
                    if ($obj->group_balance >= $j->from && $obj->group_balance <= $j->to) {
                        $child_status = $j;
                    }
                }

                //получаем разность процентных ставок
                $difference_percent = $user_status->percent - $child_status->percent;

                //проверяем, если разность больше 0, то начисляем баллы
                if ($difference_percent > 0) {

                    //считаем, сколько кредитов нужно начислить
                    $add_credits = ceil($obj->group_balance * ($difference_percent / 100));

                    //проверяем, есть ли что начислять
                    if ($add_credits > 0) {
                        //обновляем баланс пользователя
                        DB::update("user_balances")
                            ->set([
                                "credits" => DB::expr("credits + " . $add_credits),
                            ])
                            ->where("user_balances.user_id", "=", $user->id)
                            ->where("user_balances.year", "=", $year)
                            ->where("user_balances.month", "=", $month)
                            ->execute();

                        $reason = str_replace(
                            "{{user_name}}",
                            $obj->last_name . " " . $obj->name . " (" . $obj->agreement_number . ")",
                            $reasons[3]
                        );

                        //записываем в историю пополнений
                        Common::factory("history_credits")->insert([
                            "user_id" => $user->id,
                            "reason" => 3,
                            "credits" => $add_credits,
                            "text" => $reason,
                            "from_user" => $obj->id
                        ]);
                    }
                }

                //проверяем, является ли данный пользователь новичком
                if ($obj->count_balances == 0) {
                    //получаем процент, который нужно начислить за новичка
                    $percent_for_new = Config::get("costs.percent_for_new");

                    //считаем количество кредитов которые начисляются
                    $add_credits = ceil($obj->group_balance * ($percent_for_new / 100));

                    //проверяем, есть ли что начислять
                    if ($add_credits > 0) {
                        //обновляем баланс пользователя
                        DB::update("user_balances")
                            ->set([
                                "credits" => DB::expr("credits + " . $add_credits),
                            ])
                            ->where("user_balances.user_id", "=", $user->id)
                            ->where("user_balances.year", "=", $year)
                            ->where("user_balances.month", "=", $month)
                            ->execute();

                        $reason = str_replace(
                            "{{user_name}}",
                            $obj->last_name . " " . $obj->name . " (" . $obj->agreement_number . ")",
                            $reasons[1]
                        );

                        //записываем в историю пополнений
                        Common::factory("history_credits")->insert([
                            "user_id" => $user->id,
                            "reason" => 1,
                            "credits" => $add_credits,
                            "text" => $reason,
                            "from_user" => $obj->id
                        ]);
                    }

                    //добавляем родителю количество новых реферал для статистики
                    DB::update("user_balances")
                        ->set([
                            "count_new_referrals" => DB::expr("count_new_referrals + 1")
                        ])
                        ->where("user_id", "=", $user->id)
                        ->where("year", "=", $year)
                        ->where("month", "=", $month)
                        ->execute();
                }
            }
        }
    }

    /**
     *
     * Функция устанавливает общее количество рефералов за текущий месяц указанному пользователю
     * (количество рефералов указывается БЕЗ рефералов, которые отделились в отдельные структуры)
     *
     * ВНИМАНИЕ!!! КОЛИЧЕСТВО МОЖНО ПОСЧИТАТЬ ТОЛЬКО НА ДАННЫЙ МОМЕНТ, А ЗАПИСАТЬ ДЛЯ ЛЮБОГО МЕСЯЦА И ГОДА!!!
     *
     * @param $user_id - ид пользователя, которому нужно указать количество
     * @param $year - год для которого нужно записать данное значение
     * @param $month - месяц, для которого нужно указать данное значение
     */
    public function setCountReferrals($user_id, $year, $month)
    {

        $month = (int)$month;

        //получаем количество рефералов
        $count = $this->getCountReferals($user_id);

        //записываем новое количество рефералов
        DB::update("user_balances")
            ->set([
                "count_referrals" => $count
            ])
            ->where("user_id", "=", $user_id)
            ->where("year", "=", $year)
            ->where("month", "=", $month)
            ->execute();

    }

    /**
     * Функция, которая начисляет кредиты от групового дохода отделившихся стурктур
     * ВНИМАНИЕ! Начисление происходит по всем отделившимся структурам на текущий момент
     *
     * @param $year - год к которому припишется получившаяся сумма
     * @param $month - месяц, которому добавится баланс кредитов
     */
    public function accuralForSeparatedGroups($year, $month)
    {

        //получаем список причин для начислений
        $reasons = Config::get("historyCredits.reasons");

        $month = (int)$month;

        //получаем процент, который нужно начислить
        $percent = Config::get("costs.percent_for_separate");

        //получаем количество бонусных кредитов, начисляемых за отделившуюся группу
        $bonus_credits = Config::get("costs.credits_for_separate");

        //получаем все оотделившиеся структуры
        $separated_groups = DB::select(
            "separated_groups.parent_id",
            "separated_groups.user_id",
            "user_balances.group_balance",
            "users.*"
        )
            ->from("separated_groups")
            ->join("users")
            ->on("users.id", "=", "separated_groups.user_id")
            ->join("user_balances")
            ->on("user_balances.user_id", "=", "users.id")
            ->where("user_balances.year", "=", $year)
            ->where("user_balances.month", "=", $month)
            ->find_all();

        //бежим по всем отделившимся группам
        foreach ($separated_groups as $obj) {
            //расчитываем количество кредитов, которое нужно начислить
            $add_credits = ceil($obj->group_balance * ($percent / 100));

            //обновляем баланс кредитов родителя
            DB::update("user_balances")
                ->set([
                    "credits" => DB::expr("credits + " . $add_credits),
                ])
                ->where("user_balances.user_id", "=", $obj->parent_id)
                ->where("user_balances.year", "=", $year)
                ->where("user_balances.month", "=", $month)
                ->execute();

            $reason = str_replace(
                "{{user_name}}",
                $obj->last_name . " " . $obj->name . " (" . $obj->agreement_number . ")",
                $reasons[4]
            );

            //записываем в историю пополнений
            Common::factory("history_credits")->insert([
                "user_id" => $obj->parent_id,
                "reason" => 4,
                "credits" => $add_credits,
                "text" => $reason,
                "from_user" => $obj->user_id

            ]);


            //проверяем, нужно ли начислять бонус за отделившуюся группу
            if ($bonus_credits > 0) {
                //обновляем баланс кредитов родителя
                DB::update("user_balances")
                    ->set([
                        "credits" => DB::expr("credits + " . $bonus_credits),
                    ])
                    ->where("user_balances.user_id", "=", $obj->parent_id)
                    ->where("user_balances.year", "=", $year)
                    ->where("user_balances.month", "=", $month)
                    ->execute();

                $reason = str_replace(
                    "{{user_name}}",
                    $obj->last_name . " " . $obj->name . " (" . $obj->agreement_number . ")",
                    $reasons[6]
                );

                //записываем в историю пополнений
                Common::factory("history_credits")->insert([
                    "user_id" => $obj->parent_id,
                    "reason" => 6,
                    "credits" => $bonus_credits,
                    "text" => $reason,
                    "from_user" => $obj->user_id
                ]);
            }
        }
    }
}