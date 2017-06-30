<?php

namespace Wezom\Modules\Ajax\Controllers;

use Core\Arr;
use Core\HTML;
use Core\QB\DB;

class Blog extends \Wezom\Modules\Ajax {

    /**
     * Get one item information
     * $this->post['id'] => item ID
     */
    public function getItemAction() {
        $id = Arr::get($this->post, 'id', 0);
        $item = DB::select('blog.id', 'blog_i18n.name', 'blog.date', 'blog.image', ['blog_rubrics_i18n.name', 'rubric'])
                ->from('blog')
                ->join('blog_i18n', 'LEFT')->on('blog_i18n.row_id', '=', 'blog.id')
                ->join('blog_rubrics', 'LEFT')->on('blog_rubrics.id', '=', 'blog.rubric_id')
                ->join('blog_rubrics_i18n', 'LEFT')->on('blog_rubrics_i18n.row_id', '=', 'blog_rubrics.id')
                ->where('blog.id', '=', $id)
                ->where('blog_i18n.language', '=', \I18n::$default_lang)
                ->where('blog_rubrics_i18n.language', '=', \I18n::$default_lang)
                ->find();
        die(json_encode([
            'success' => true,
            'item' => [
                'id' => $item->id,
                'name' => $item->name,
                'rubric' => $item->rubric,
                'link' => HTML::link('wezom/blog/edit/' . $item->id),
                'date' => $item->date ? date('d.m.Y', $item->date) : NULL,
                'image' => is_file(HOST . HTML::media('images/blog/small/' . $item->image, false)) ? HTML::media('images/blog/small/' . $item->image) : NULL,
                'image_big' => is_file(HOST . HTML::media('images/blog/medium/' . $item->image, false)) ? HTML::media('images/blog/big/' . $item->image) : NULL,
            ],
        ]));
    }

    /**
     * Get items list by some parameters
     * $this->post['id'] => current item ID
     * $this->post['parent_id'] => search item group ID
     * $this->post['search'] => search item artikul or name (or part of it)
     */
    public function searchItemsAction() {
        $parent_id = (int) Arr::get($this->post, 'rubric_id');
        $search = Arr::get($this->post, 'search');
        $limit = (int) Arr::get($this->post, 'limit', 1);
        $page = (int) Arr::get($this->post, 'page', 1);
        $offset = ($page - 1) * $limit;
        if (!$parent_id && !trim($search)) {
            $this->error([
                'count' => 0,
                'items' => [],
            ]);
        }

        $result = DB::select('blog.id', 'blog_i18n.name', 'blog.image', ['blog_rubrics_i18n.name', 'rubric'])
                ->from('blog')
                ->join('blog_i18n', 'LEFT')->on('blog_i18n.row_id', '=', 'blog.id')
                ->join('blog_rubrics', 'LEFT')->on('blog_rubrics.id', '=', 'blog.rubric_id')
                ->join('blog_rubrics_i18n', 'LEFT')->on('blog_rubrics_i18n.row_id', '=', 'blog_rubrics.id')
                ->where('blog_i18n.language', '=', \I18n::$default_lang)
                ->where('blog_rubrics_i18n.language', '=', \I18n::$default_lang);
        if ($parent_id > 0) {
            $result->where('blog.rubric_id', '=', $parent_id);
        }
        if (trim($search)) {
            $result->where('blog_i18n.name', 'LIKE', '%' . $search . '%');
        }

        $result = $result
                ->group_by('blog.id')
                ->limit($limit)
                ->offset($offset)
                ->find_all();

        $count = DB::select([DB::expr('COUNT(DISTINCT blog.id)'), 'count'])
                        ->from('blog')
                        ->join('blog_i18n', 'LEFT')->on('blog_i18n.row_id', '=', 'blog.id');
        if ($parent_id > 0) {
            $count->where('blog.rubric_id', '=', $parent_id);
        }
        if (trim($search)) {
            $count->where('blog_i18n.name', 'LIKE', '%' . $search . '%');
        }
        $count = $count->count_all();

        $items = [];
        foreach ($result AS $obj) {
            $items[] = [
                'id' => $obj->id,
                'name' => $obj->name,
                'rubric' => $obj->rubric,
                'image' => is_file(HOST . HTML::media('images/blog/small/' . $obj->image, false)) ? HTML::media('images/blog/small/' . $obj->image) : NULL,
            ];
        }
        $this->success([
            'count' => $count,
            'items' => $items,
        ]);
    }

}
