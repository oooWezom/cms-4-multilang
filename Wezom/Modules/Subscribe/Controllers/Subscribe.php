<?php
    namespace Wezom\Modules\Subscribe\Controllers;

    use Core\Common;
    use Core\Config;
    use Core\HTML;
    use Core\Route;
    use Core\Widgets;
    use Core\Message;
    use Core\Arr;
    use Core\HTTP;
    use Core\View;
    use Core\Pager\Pager;
    use Core\Email;

    use Wezom\Modules\Subscribe\Models\Subscribe AS Model;
    use Wezom\Modules\Subscribe\Models\Subscribers;

    class Subscribe extends \Wezom\Modules\Base {

        public $tpl_folder = 'Subscribe/Mail';
        public $page;
        public $limit;
        public $offset;

        function before() {
            parent::before();
            $this->_seo['h1'] = __('Рассылка писем');
            $this->_seo['title'] = __('Рассылка писем');
            $this->setBreadcrumbs(__('Рассылка писем'), 'wezom/'.Route::controller().'/index');
            $this->page = (int) Route::param('page') ? (int) Route::param('page') : 1;
            $this->limit = Config::get('basic.limit_backend');
            $this->offset = ($this->page - 1) * $this->limit;
        }

        function indexAction () {
            $date_s = NULL; $date_po = NULL; $status = NULL;
            if ( Arr::get($_GET, 'date_s') ) { $date_s = strtotime( Arr::get($_GET, 'date_s') ); }
            if ( Arr::get($_GET, 'date_po') ) { $date_po = strtotime( Arr::get($_GET, 'date_po') ); }
            if ( isset($_GET['status']) ) { $status = Arr::get($_GET, 'status', 1); }
            $page = (int) Route::param('page') ? (int) Route::param('page') : 1;
            $count = Model::countRows($status, $date_s, $date_po);
            $result = Model::getRows($status, $date_s, $date_po, 'id', 'DESC', $this->limit, ($page - 1) * $this->limit);
            $pager = Pager::factory( $page, $count, $this->limit )->create();
           // $this->_toolbar = Widgets::get( 'Toolbar/List', array( 'add' => 1, 'delete' => 1 ) );
            $this->_content = View::tpl(
                [
                    'result' => $result,
                    'tpl_folder' => $this->tpl_folder,
                    'tablename' => Model::$table,
                    'count' => $count,
                    'pager' => $pager,
                    'pageName' => $this->_seo['h1'],
                ], $this->tpl_folder.'/Index');
        }

        function sendAction () {
            $emails = [];
            $list = [];
            if ( $_POST ) {
                $post = $_POST['FORM'];
                $subscribers = Subscribers::getRows(1);
                foreach( $subscribers AS $obj ) {
                    if( filter_var( $obj->email, FILTER_VALIDATE_EMAIL ) AND !in_array( $obj->email, $emails ) ) {
                        $emails[] = $obj;
                        $list[] = $obj->email;
                    }
                }
                if( Model::valid($post, $emails) ) {
                    $data = $post;
                    $data['count_emails'] = count( $list );
                    $data['emails'] = implode( ';', $list );
                    Model::insert($data);
                    foreach( $emails AS $obj ) {
                        $link = 'http://' . Arr::get( $_SERVER, 'HTTP_HOST' ) . '/unsubscribe/hash/' . $obj->hash;
                        $from = ['{{unsubscribe}}', '{{site}}', '{{date}}'];
                        $to = [$link, Arr::get( $_SERVER, 'HTTP_HOST' ), date('d.m.Y')];
                        $message = str_replace( $from, $to, Arr::get( $post, 'text' ) );
                        $subject = str_replace( $from, $to, Arr::get( $post, 'subject' ) );
                        if( !Config::get('main.cron') ) {
                            Email::send( $subject, $message, $obj->email );
                        } else {
                            $data = [
                                'subject' => $subject,
                                'text' => $message,
                                'email' => $obj->email,
                            ];
                            Common::factory(Config::get('main.tableCron'))->insert($data);
                        }
                    }
                    Message::GetMessage(1, __('Письмо успешно разослано ').$data['count_emails'].' подписчикам!');
                    HTTP::redirect('wezom/'.Route::controller().'/'.Route::action());
                }
                $result = Arr::to_object( $post );
            } else {
                $result = Arr::to_object( ['subscribers' => 1]);
            }
            $this->_toolbar = Widgets::get( 'Toolbar_Subscribe' );
            $this->_seo['h1'] = __('Отправка письма');
            $this->_seo['title'] = __('Отправка письма');
            $this->setBreadcrumbs(__('Отправка письма'), 'wezom/'.Route::controller().'/add');
            $this->_content = View::tpl(
                [
                    'obj' => $result,
                    'tpl_folder' => $this->tpl_folder,
                ], $this->tpl_folder.'/Send');
        }
        
    }