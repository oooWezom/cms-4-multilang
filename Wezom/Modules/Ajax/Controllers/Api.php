<?php

namespace Wezom\Modules\Ajax\Controllers;

use Core\Config;
use Core\QB\DB;
use Wezom\Modules\Ajax\Helpers\Translates;

/**
 * Class Api
 * @package Wezom\Modules\Ajax\Controllers
 */
class Api extends \Wezom\Modules\Ajax
{

    public function translatesAction()
    {
        $model = new Translates(HOST . '/Wezom', 'TranslatesBackend');
        $model->database = true;
        $model->prepareTranslates();
        foreach (Config::get('i18n.languages') as $lang) {
            $model->generateTranslatesFor($lang['alias']);
        }
        $this->success('Done!');
    }

    public function translatesForFrontendAction()
    {
        $languages = Translates::getFrontendLanguages();
        $model = new Translates(HOST . '/', 'Translates', ['Wezom']);
        $model->prepareTranslates();
        foreach ($languages as $lang) {
            $model->generateTranslatesFor($lang['alias']);
        }
        $this->success('Done!');
    }

}