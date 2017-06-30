<?php
namespace Core\Pager;

use Core\Route;
use Core\Arr;
use Core\View;

/**
 *  Example:
 *  $pager = Plugins\Pager\Pager::factory(3, 10, 2)->create();
 */
class Pager
{

    public $_current; // Current page
    public $_next; // Next page
    public $_previous; // Previous page
    public $_total; // Total count of items
    public $_per_page; // Count of items per page
    public $_total_pages; // Total count of pages

    public $_uri; // Current URI before "?"
    public $_get; // Part of URI after "?" and with "?"

    public $_count_out = 3; // Number of page links in the begin and end of whole range
    public $_count_in = 2; // Number of page links on each side of current page
    public $_navigation = true;


    public static function factory($current, $total, $per_page)
    {
        return new Pager($current, $total, $per_page);
    }


    public function __construct($current, $total, $per_page)
    {
        if (!$per_page) {
            return null;
        }
        try {
            $this->_current = (int)$current;
            $this->_total = (int)$total;
            $this->_per_page = (int)$per_page;
            $this->_total_pages = ceil($this->_total / $this->_per_page);
            if ($this->_navigation) {
                $this->_next = $this->_current < $this->_total_pages ? $this->_current + 1 : false;
                $this->_previous = $this->_current > 1 ? $this->_current - 1 : false;
            }
            $this->setURI();
        } catch (\Exception $e) {
            die($e->getMessage());
        }
    }


    public function setURI()
    {
        $uri = Arr::get($_SERVER, 'REQUEST_URI');
        $uri = explode('?', $uri);
        $this->_get = Arr::get($uri, 1, null) ? '?' . Arr::get($uri, 1, null) : null;
        $this->_uri = Arr::get($uri, 0, null);
        if (preg_match('/\/page\/' . $this->_current . '/', $this->_uri, $matches)) {
            $this->_uri = str_replace($matches, '', $this->_uri);
        }
    }


    public function create()
    {
        if ($this->_total_pages < 2) {
            return '';
        }
        $data = [];
        foreach ($this as $key => $value) {
            $data[$key] = $value;
        }
        $data['page'] = $this;
        if (APPLICATION == 'backend') {
            return View::core($data, 'Pager' . DS . 'AdminView');
        }
        return View::core($data, 'Pager' . DS . 'View');
    }


    public function url($page = 1)
    {
        // Clean the page number
        $page = max(1, (int)$page);

        // No page number in URLs to first page
        if ($page === 1) {
            return $this->_uri . $this->_get;
        }

        // Generate URI with new page
        return $this->_uri . '/page/' . $page . $this->_get;
    }

}