<?php
namespace Modules\Content\Controllers;

use Core\Route;
use Core\View;
use Core\Config;
use Core\HTTP;
use Modules\Base;
use Modules\Content\Models\Content AS Model;
use Modules\Content\Models\Control;

class Content extends Base
{
    public function indexAction() {
        // Check for existance
        $simple = true;
        $page = Model::getRowSimple(Route::param('alias'), 'alias');
        if( !$page ) {
            $simple = false;
            $page = Control::getRowSimple(Route::param('alias'), 'alias');
            if( !$page ) {
                return Config::error();
            }
        }
        if ($page->status != 1) {
            HTTP::redirect('/', 301);
        }
        // Seo
        $this->_seo['h1'] = $page->h1;
        $this->_seo['title'] = $page->title;
        $this->_seo['keywords'] = $page->keywords;
        $this->_seo['description'] = $page->description;
        $this->generateParentBreadcrumbs( $page->parent_id, 'content', 'parent_id' );
        $this->setBreadcrumbs( $page->name );
        if($simple) {
            // Add plus one to views
            $page = Model::addView($page);
            // Get content page children
            $kids = Model::getKids($page->id);
        } else {
            $kids = array();
        }
        // Render template
        $this->_content = View::tpl(['text' => $page->text, 'kids' => $kids], 'Content/Page' );
    }
}
    