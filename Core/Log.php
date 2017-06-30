<?php
namespace Core;

use Core\QB\DB;

class Log
{

    // Add row to log of:
    // - orders,
    // - simple orders,
    // - contact form,
    // - registrations,
    // - callback form,
    // - question about item,
    // - comment on item page,
    // - new subscriber
    public static function add($name, $link, $type = 0)
    {
        // Check incoming data
        if (!$name or !$link) {
            return;
        }
        // Save log
        return DB::insert('log', ['name', 'link', 'type', 'ip', 'created_at'])->values([$name, $link, $type, System::getRealIP(), time()])->execute();
    }


    // Delete row from log
    public static function delete($id)
    {
        // Check id for not doing query to database if we don't need it
        if (!$id) {
            return false;
        }
        // Set field "deleted" as 1
        return DB::update('log')->set(['deleted' => 1, 'updated_at' => time()])->where('id', '=', $id)->execute();
    }


    // Get icon for log list in header
    public static function icon($type)
    {
        switch ($type) {
            // Green plus - registration
            case '1':
                return '<span class="label label-success"><i class="fa fa-plus"></i></span>';
            // Blue mail - contact form
            case '2':
                return '<span class="label label-info"><i class="fa fa-envelope"></i></span>';
            // Purple mail - callback form
            case '3':
                return '<span class="label label-magenta"><i class="fa fa-envelope"></i></span>';
            // Blue rupor - new subscriber
            case '4':
                return '<span class="label label-default"><i class="fa fa-bullhorn"></i></span>';
            // Black question sign - question about item
            case '5':
                return '<span class="label label-inverse"><i class="fa fa-fixed-width">&#xf128;</i></span>';
            // Orange flash - new comment for item
            case '6':
                return '<span class="label label-primary"><i class="fa fa-bolt"></i></span>';
            // Orange warning - new simple order
            case '7':
                return '<span class="label label-warning"><i class="fa fa-warning"></i></span>';
            // Red warning - new order
            case '8':
                return '<span class="label label-danger"><i class="fa fa-warning"></i></span>';
            // Without icon
            default:
                return '';
        }
    }

}