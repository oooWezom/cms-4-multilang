<?php

namespace Wezom\Modules\Ajax\Controllers;

use Core\Common;
use Core\Config;
use Core\Cookie;
use Core\Email;
use Core\HTML;
use Core\Text;
use Core\Arr;
use Core\Message;
use Core\QB\DB;
use Core\Support;
use Core\Files;
use Core\User;
use Wezom\Modules\Ajax\Helpers\Translates;
use Wezom\Modules\Seo\Models\Sitemap;

class General extends \Wezom\Modules\Ajax {

    public function sendNewPasswordAction() {
        $password = trim(Arr::get($_POST, 'password'));
        if (!$password) {
            $password = Text::random('alnum', 12);
        }
        $user_id = Arr::get($_POST, 'uid');
        $user = Common::factory('users')->getRow($user_id);
        if (!$user) {
            $this->error(__('К сожалению, произошла ошибка. Пожалуйста обновите страницу и попробуйте еще раз!'));
        }

        User::factory()->update_password($user_id, $password);

        Email::sendTemplate(26, [
            '{{site}}' => Arr::get($_SERVER, 'HTTP_HOST'),
            '{{date}}' => date('d.m.Y'),
            '{{password}}' => $password
                ], $user->email);

        $r = Email::sendTemplate(26, [
                    '{{site}}' => Arr::get($_SERVER, 'HTTP_HOST'),
                    '{{date}}' => date('d.m.Y'),
                    '{{password}}' => $password
                        ], $user->email);

        if (!$r) {
            $this->error(__('Пароль был изменен, но не был отправлен на почту пользователя. Причина: отключен шаблон в разделе "Шаблоны писем"'));
        }

        $this->success(__('Пароль успешно отправлен!'));
    }

    public function generateCodeAction() {
        $this->success([
            'code' => Text::random('alnum', 12),
        ]);
    }

    /**
     * Get count of orders for last 12 months
     */
    public function ordersChartAction() {
        $months = array(NULL, 'Янв', 'Фев', 'Мар', 'Апр', 'Май', 'Июн', 'Июл', 'Авг', 'Сен', 'Окт', 'Ноя', 'Дек');
        $month = date('n');
        $year = date('y');
        $date = strtotime('01.' . Support::addZero($month) . '.' . (date('Y') - 1));
        $result = DB::select(
                        array(DB::expr('COUNT(`id`)'), 'count'), array(DB::expr('FROM_UNIXTIME(`created_at`, "%c")'), 'month'), array(DB::expr('FROM_UNIXTIME(`created_at`, "%Y")'), 'year')
                )
                ->from('orders')
                ->where('created_at', '>=', $date)
                ->order_by('created_at')
                ->group_by(DB::expr('month'))
                ->group_by(DB::expr('year'))
                ->find_all();

        $orders = array();
        foreach ($result AS $obj) {
            $orders[$obj->month . '.' . $obj->year] = $obj->count;
        }
        $chart = array();
        if ($month > 0) {
            for ($i = $month + 1; $i <= 12; $i++) {
                $chart['months'][] = $months[$i] . ' ' . (date('Y') - 1);
                $chart['count'][] = (int) Arr::get($orders, $i . '.' . (date('Y') - 1), 0);
            }
        }
        for ($i = 1; $i <= $month; $i++) {
            $chart['months'][] = $months[$i] . ' ' . date('Y');
            $chart['count'][] = (int) Arr::get($orders, $i . '.' . date('Y'), 0);
        }

        $this->success($chart);
    }

    /**
     * Get data for visitors chart on main page
     */
    public function visitorsMainDataAction() {
        $days = 14;
        $now = strtotime(date('d.m.Y'));
        $today = date('j');
        $month = date('n');
        $year = date('Y');
        $chart = [];
        if ($today > $days) {
            for ($i = $today - $days + 1; $i <= $today; $i ++) {
                $chart[Support::addZero($i) . '.' . Support::addZero($month) . '.' . $year] = [
                    'visits' => 0,
                    'visitors' => 0,
                ];
            }
        } else {
            $days1 = $today;
            $days2 = $days - $today + 1;
            $last_month = $month - 1;
            $last_year = $year;
            $countDays = date('t', strtotime('01.' . $last_month . '.' . $last_year));
            if (!$last_month) {
                $last_month = 12;
                $last_year = $year - 1;
            }
            for ($i = $countDays - $days2 + 1; $i <= $countDays; $i ++) {
                $chart[Support::addZero($i) . '.' . Support::addZero($last_month) . '.' . $last_year] = [
                    'visits' => 0,
                    'visitors' => 0,
                    'hits' => 0,
                    'unique_hits' => 0,
                ];
            }
            for ($i = $today - $days1 + 1; $i <= $today; $i ++) {
                $chart[Support::addZero($i) . '.' . Support::addZero($month) . '.' . $year] = [
                    'visits' => 0,
                    'visitors' => 0,
                    'hits' => 0,
                    'unique_hits' => 0,
                ];
            }
        }
        $result = DB::select([DB::expr('COUNT(ip)'), 'visits'], 'ip', [DB::expr('FROM_UNIXTIME(`created_at`, "%d.%m.%Y")'), 'date'])
                ->from('visitors_hits')
                ->where('created_at', '>=', $now - $days * 24 * 60 * 60)
                ->group_by(DB::expr('date'))
                ->find_all();
        foreach ($result AS $obj) {
            $chart[$obj->date]['visits'] += $obj->visits;
        }
        $result = DB::select([DB::expr('COUNT(DISTINCT ip)'), 'visitors'], [DB::expr('FROM_UNIXTIME(`created_at`, "%d.%m.%Y")'), 'date'])
                ->from('visitors_hits')
                ->where('created_at', '>=', $now - $days * 24 * 60 * 60)
                ->group_by(DB::expr('date'))
                ->find_all();
        foreach ($result AS $obj) {
            $chart[$obj->date]['visitors'] += $obj->visitors;
        }
        $result = [];
        foreach ($chart AS $date => $row) {
            $result['dates'][] = $date;
            $result['visits'][] = $row['visits'];
            $result['visitors'][] = $row['visitors'];
        }
        $chart = $result;
        $result = DB::select([DB::expr('COUNT(ip)'), 'hits'], [DB::expr('COUNT(DISTINCT ip)'), 'unique'])
                ->from('visitors_hits')
                ->where('created_at', '>=', $now - $days * 24 * 60 * 60)
                ->find_all();
        foreach ($result AS $obj) {
            $chart['hits'] += $obj->hits;
            $chart['unique_hits'] += $obj->unique;
        }
        $result = DB::select([DB::expr('COUNT(ip)'), 'hits'], [DB::expr('COUNT(DISTINCT ip)'), 'unique'])
                ->from('visitors_hits')
                ->where('created_at', '>=', time() - 24 * 60 * 60)
                ->find_all();
        foreach ($result AS $obj) {
            $chart['hits_tf'] += $obj->hits;
            $chart['unique_hits_tf'] += $obj->unique;
        }
        $chart['hits'] = number_format($chart['hits'], 0, '.', ',');
        $chart['unique_hits'] = number_format($chart['unique_hits'], 0, '.', ',');
        $chart['hits_tf'] = number_format($chart['hits_tf'], 0, '.', ',');
        $chart['unique_hits_tf'] = number_format($chart['unique_hits_tf'], 0, '.', ',');
        $this->success($chart);
    }

    /**
     * Remove ALL minified files from /Media/cache folder
     */
    public function clearMinifyCacheAction() {
        \Minify_Core::clearCache();
        $this->success();
    }

    /**
     * Transliterate incoming string
     * $this->post['source'] => incoming string
     */
    public function translitAction() {
        $this->success([
            'result' => Text::translit(trim(Arr::get($this->post, 'source')), Arr::get($this->post, 'rules', '')),
        ]);
    }

    /**
     * Set status to chosen row
     * $this->post['id'] => row ID
     * $this->post['table'] => table where status will be change
     * $this->post['current'] => current status 0/1
     */
    public function setStatusAction() {
        if (!isset($this->post['id'])) {
            die('Не указаны данные записи');
        }
        $status = (int) Arr::get($this->post, 'current', 0);
        if ($status) {
            $status = 0;
        } else {
            $status = 1;
        }
        $id = Arr::get($this->post, 'id', 0);
        $table = Arr::get($this->post, 'table', 0);
        DB::update($table)->set(['status' => $status])->where('id', '=', $id)->execute();
        $this->success([
            'status' => $status
        ]);
    }

    /**
     * Delete rows from table
     * $this->post['ids'] => array with IDs of rows we want to delete
     * $this->post['table'] => table where we want to delete rows
     */
    public function deleteMassAction() {
        if (!isset($this->post['ids'])) {
            die(__('Не указаны данные записи'));
        }
        $ids = Arr::get($this->post, 'ids', 0);
        $table = Arr::get($this->post, 'table', 0);
        if ($ids && is_array($ids) && count($ids)) {
            if ($table == 'catalog') {
                $images = DB::select('image')->from('catalog_images')->select('image')->where('catalog_id', 'IN', $ids)->find_all();
                foreach ($images AS $im) {
                    Files::deleteImage($table, $im->image);
                }
            } else if ($table == 'gallery') {
                $images = DB::select('image')->from($table)->where('id', 'IN', $ids)->find_all();
                foreach ($images AS $im) {
                    Files::deleteImage($table, $im->image);
                }
                $images = DB::select('image')->from('gallery_images')->where('gallery_id', 'IN', $ids)->find_all();
                foreach ($images AS $im) {
                    Files::deleteImage('gallery_images', $im->image);
                }
            } else if (Config::get('images.' . $table) && is_array(Config::get('images.' . $table))) {
                if (Common::checkField($table, 'image')) {
                    $images = DB::select('image')->from($table)->where('id', 'IN', $ids)->find_all();
                    foreach ($images AS $im) {
                        Files::deleteImage($table, $im->image);
                    }
                }
            }
            DB::delete($table)->where('id', 'IN', $ids)->execute();
            Message::GetMessage(1, __('Данные удалены!'));
        }
        $this->success();
    }

    /**
     * Set status to chosen rows
     * $this->post['ids'] => array with IDs of rows where we want to update status
     * $this->post['table'] => table where status will be change
     * $this->post['status'] => status we want
     */
    public function setStatusMassAction() {
        if (!isset($this->post['ids'])) {
            die(__('Не указаны данные записи'));
        }
        $status = (int) Arr::get($this->post, 'status', 0);
        $ids = Arr::get($this->post, 'ids', 0);
        $table = Arr::get($this->post, 'table', 0);
        if (!empty($ids)) {
            DB::update($table)->set(['status' => $status])->where('id', 'IN', $ids)->execute();
            Message::GetMessage(1, __('Статусы изменены!'));
        }
        $this->success();
    }

    /**
     * Method to get uri with date parameters (filter widget in some lists)
     * $this->post["uri"] => current URL
     * $this->post["from"] => date from
     * $this->post["to"] => date to
     */
    public function getURIAction() {
        $uri = Arr::get($this->post, "uri");
        $date_s = Arr::get($this->post, "from");
        $date_po = Arr::get($this->post, "to");
        $uri = Support::generateLink('date_s', $date_s, $uri);
        $uri = Support::generateLink('date_po', $date_po, $uri);
        $this->success([
            'uri' => $uri,
        ]);
    }

    /**
     * Set sortable in some table
     * $this->post['table'] => table where we want to sort rows
     * $this->post['json'] => tree with IDs in right order and depth in JSON format
     */
    public function sortableAction() {
        $table = Arr::get($this->post, 'table');
        $json = Arr::get($this->post, 'json');
        $arr = json_decode(stripslashes($json), true);

        function saveSort($arr, $table, $parentID, $i = 0) {
            foreach ($arr AS $a) {
                $inner = Common::checkField($table, 'parent_id');
                if ($inner) {
                    $data = ['sort' => $i, 'parent_id' => $parentID];
                } else {
                    $data = ['sort' => $i];
                }
                $id = Arr::get($a, 'id');
                Common::factory($table)->update($data, $id);
                $i++;
                $children = Arr::get($a, 'children', []);
                if (count($children)) {
                    if (!$inner) {
                        $i = saveSort($children, $table, $id, $i);
                    } else {
                        saveSort($children, $table, $id);
                    }
                }
            }
            return $i;
        }

        saveSort($arr, $table, 0);
        $this->success();
    }

    /**
     * Simple sortable without depth
     */
    public function sortableSimpleAction() {
        $sort = Arr::get($this->post, 'sort');
        $params = Arr::get($this->post, 'params');
        if (!$sort OR ! Arr::get($params, 'table')) {
            $this->error('Error!');
        }
        parse_str($sort, $sort);
        foreach (Arr::get($sort, 'sort') as $k => $id) {
            DB::update(Arr::get($params, 'table'))->set(['sort' => $k])->where('id', '=', $id)->execute();
        }
        $this->success();
    }

    /**
     * Authorization to admin panel
     * $this->post['login'] => user login
     * $this->post['password'] => user password
     * $this->post['remember'] => does user want to remember his password
     */
    public function loginAction() {
        $login = Arr::get($this->post, 'login');
        $password = Arr::get($this->post, 'password');
        $remember = Arr::get($this->post, 'remember');
        $u = User::factory();
        $user = $u->get_user_if_isset($login, $password, 1);
        if (!$user OR $user->role == 'user') {
            $this->error([
                'msg' => __('Логин или пароль введены неверно!'),
            ]);
        }
        $u->auth($user, $remember);
        $this->success();
    }

    /**
     * Switch any field between 0 & 1
     * $this->post['id'] => row ID
     * $this->post['table'] => table where field will be change
     * $this->post['field'] => field to change
     */
    public function change_fieldAction() {
        $id = (int) Arr::get($this->post, 'id');
        $field = Arr::get($this->post, 'field');
        $table = Arr::get($this->post, 'table');
        if (!$id) {
            die(__('Не указаны данные записи'));
        }
        $old = DB::select($field)->from($table)->where('id', '=', $id)->find();
        if (!$old) {
            die('No data to change!');
        }
        $old = $old->$field;
        $new = $old == 1 ? 0 : 1;
        $data = [];
        $data[$field] = $new;
        Common::factory($table)->update($data, $id);
        $this->success([
            'current' => $new
        ]);
    }

    public function changeLanguageAction() {
        $lang = Arr::get($_POST, 'lang', Config::get('i18n.default'));
        Cookie::set('backend_lang', $lang, 7 * 24 * 60 * 60);
        $this->success();
    }

    public function saveTranslationAction($frontend = true) {
        $lang = Arr::get($_POST, 'lang');
        $key = Arr::get($_POST, 'key');
        $value = Arr::get($_POST, 'value');

        if ($frontend) {
            $path = HOST . '/Plugins/I18n/Translates/' . $lang . '/general.php';
        } else {
            $path = HOST . '/Plugins/I18n/TranslatesBackend/' . $lang . '/general.php';
        }

        if (!is_file($path)) {
            die(json_encode([
                'success' => false,
                'msg' => 'No such file!',
            ]));
        }

        $array = include $path;
        $array[$key] = $value;

        $text = "<?php";
        $text .= "\n\treturn array(";
        foreach ($array AS $key => $value) {
            $text .= "\n\t\t'" . str_replace("'", "\'", $key) . "' => '" . str_replace("'", "\'", $value) . "',";
        }
        $text .= "\n\t);";
        file_put_contents($path, $text);
        die(json_encode([
            'success' => true,
            'msg' => 'Done!',
        ]));
    }

    public function addTranslationAction($frontend = true) {
        if ($frontend) {
            $languages = Translates::getFrontendLanguages();
        } else {
            $languages = Config::get('i18n.languages');
        }
        $_key = Arr::get($_POST, 'key');
        foreach ($languages AS $lang) {
            if ($frontend) {
                $path = HOST . '/Plugins/I18n/Translates/' . $lang['alias'] . '/general.php';
            } else {
                $path = HOST . '/Plugins/I18n/TranslatesBackend/' . $lang['alias'] . '/general.php';
            }
            if (!is_file($path)) {
                continue;
            }
            $array = include $path;
            $array[$_key] = Arr::get($_POST, $lang['alias']);
            $text = "<?php";
            $text .= "\n\treturn array(";
            foreach ($array AS $key => $value) {
                $text .= "\n\t\t'" . str_replace("'", "\'", $key) . "' => '" . str_replace("'", "\'", $value) . "',";
            }
            $text .= "\n\t);";
            file_put_contents($path, $text);
        }
        die(json_encode([
            'success' => true,
            'msg' => 'Done!',
        ]));
    }

    public function saveTranslationBackendAction() {
        return $this->saveTranslationAction(false);
    }

    public function addTranslationBackendAction() {
        return $this->addTranslationAction(false);
    }
	
	public function setStatusSitemapAction() {
		if (!isset($this->post['id'])) { die ('Не указаны данные записи'); }
		$status = (int) Arr::get( $this->post, 'current', 0 );
		if( $status ) {
			$status = 0;
		} else {
			$status = 1;
		}
		$id = Arr::get( $this->post, 'id', 0 );
		$ids = Sitemap::updateStatus($id, $status);
		$this->success([
			'status' => $status,
			'ids' => $ids,
		]);
	}

    // Get items by parent_id
//        public function getItemsAction() {
//            $id = Arr::get($this->post, 'parent_id');
//            $result = DB::select('catalog.*', 'catalog_images.image')->from('catalog')
//                ->join('catalog_images')
//                ->on('catalog_images.catalog_id', '=', 'catalog.id')
//                ->on('catalog_images.main', '=', DB::expr(1))
//                ->where('parent_id', '=', $id)
//                ->order_by('created_at', 'DESC')
//                ->find_all();
//            $data = array();
//            foreach( $result AS $obj ) {
//                $data[] = array(
//                    // 'url' => '/catalog/'.$obj->alias,
//                    'image' => is_file(HOST.HTML::media('images/catalog/medium/'.$obj->image)) ? HTML::media('images/catalog/medium/'.$obj->image) : '',
//                    'name' => $obj->name,
//                    'cost' => $obj->cost,
//                    'id' => $obj->id,
//                );
//            }
//            die(json_encode(array(
//                'success' => true,
//                'result' => $data,
//            )));
//        }
    // Get sizes by catalog_id
//        public function getItemSizesAction() {
//            $catalog_id = (int) Arr::get($this->post, 'catalog_id');
//            $result = DB::select('sizes.*')->from('sizes')
//                ->join('catalog_sizes')
//                ->on('catalog_sizes.size_id', '=', 'sizes.id')
//                ->on('catalog_sizes.catalog_id', '=', DB::expr($catalog_id))
//                ->order_by('name')
//                ->find_all();
//            $data = array();
//            foreach( $result AS $obj ) {
//                if( (int) $obj->id ) {
//                    $data[] = array(
//                        'id' => $obj->id,
//                        'name' => $obj->name,
//                    );
//                }
//            }
//            die(json_encode(array(
//                'success' => true,
//                'result' => $data,
//            )));
//        }
}
