<?php
namespace Forms;

use Core\HTML;
use Core\Text;

class Builder
{

    public static function open(array $attributes = null)
    {
        if (!isset($attributes['id'])) {
            $attributes['id'] = 'myForm';
        }
        $classes = ['rowSection', 'validat'];
        if (isset($attributes['class'])) {
            if (is_array($attributes['class'])) {
                $classes = array_merge($classes, $attributes['class']);
            } else {
                $classes[] = $attributes['class'];
            }
        }
        $attributes['class'] = $classes;
        if (!isset($attributes['method'])) {
            $attributes['method'] = 'POST';
        }
        if (!isset($attributes['enctype'])) {
            $attributes['enctype'] = 'multipart/form-data';
        }
        return Form::open($attributes);
    }

    public static function alias(array $attributes = null, $labelOptions = false, $buttonAttrs = [])
    {
        $attributes = Builder::req($attributes);
        $attributes['class'][] = 'translitConteiner';
        $input = '<div class="input-group">';
        $input .= Form::input($attributes);
        $input .= '<span class="input-group-btn">';
		$attrs=$buttonAttrs;
		$attrs['class'] = 'btn translitAction';
		$attrs['type'] = 'button';
        $input .= Form::button('Заполнить автоматически', $attrs);
        $input .= '</span>';
        $input .= '</div>';
        if ($labelOptions === false) {
            return $input;
        }
        $label = Builder::label($attributes, $labelOptions);
        return $label . $input;
    }

    public static function input(array $attributes = null, $labelOptions = false)
    {
        $attributes = Builder::req($attributes);
        $input = Form::input($attributes);
        if ($labelOptions === false) {
            return $input;
        }
        $label = Builder::label($attributes, $labelOptions);
        return $label . $input;
    }

    public static function hidden(array $attributes = null, $labelOptions = false)
    {
        $attributes['type'] = 'hidden';
        return Builder::input($attributes, $labelOptions);
    }

    public static function password(array $attributes = null, $labelOptions = false)
    {
        $attributes['type'] = 'password';
        return Builder::input($attributes, $labelOptions);
    }

    public static function file(array $attributes = null, $labelOptions = false)
    {
        $attributes['type'] = 'file';
        return Builder::input($attributes, $labelOptions);
    }

    public static function checkbox($checked = false, array $attributes = null, $labelOptions = false)
    {
        if ($checked) {
            $attributes[] = 'checked';
        }
        $attributes['type'] = 'checkbox';
        return Builder::input($attributes, $labelOptions);
    }

    public static function radio($checked = false, array $attributes = null, $labelOptions = false)
    {
        if ($checked) {
            $attributes[] = 'checked';
        }
        $attributes['type'] = 'radio';
        return Builder::input($attributes, $labelOptions);
    }

    public static function bool($checked, $name = null, $text = null)
    {
        $status = '<label class="control-label">' . ($text ?: __('Опубликовано')) . '</label>';
        $status .= '<label class="checkerWrap-inline">';
        $status .= Builder::radio(!(bool)$checked, ['name' => $name ?: 'status', 'value' => 0]);
        $status .= __('Нет');
        $status .= '</label>';
        $status .= '<label class="checkerWrap-inline">';
        $status .= Builder::radio((bool)$checked, ['name' => $name ?: 'status', 'value' => 1]);
        $status .= __('Да');
        $status .= '</label>';
        return $status;
    }

    public static function textarea(array $attributes = null, $labelOptions = false)
    {
        $attributes = Builder::req($attributes);
        $body = '';
        if (isset($attributes['value'])) {
            $body = $attributes['value'];
            unset($attributes['value']);
        }
        $textarea = Form::textarea($body, $attributes);
        if ($labelOptions === false) {
            return $textarea;
        }
        $label = Builder::label($attributes, $labelOptions);
        return $label . $textarea;
    }

    public static function tiny(array $attributes = null, $labelOptions = false)
    {
        $tinyClass = 'tinymceEditor';
        if (!isset($attributes['class'])) {
            $attributes['class'] = $tinyClass;
        } else if (is_array($attributes['class'])) {
            $attributes['class'][] = $tinyClass;
        } else {
            $attributes['class'] = [$attributes['class'], $tinyClass];
        }
        if (!isset($attributes['style'])) {
            $attributes['style'] = 'height: 400px;';
        }
        return Builder::textarea($attributes, $labelOptions);
    }

    public static function select($options = null, $selected = null, array $attributes = null, $labelOptions = false)
    {
        $attributes = Builder::req($attributes);
        $select = Form::select($options, $selected, $attributes);
        if ($labelOptions === false) {
            return $select;
        }
        $label = Builder::label($attributes, $labelOptions);
        return $label . $select;
    }

    public function label(array $attributes = null, $labelOptions = false)
    {
        if ($labelOptions === null || !$labelOptions) {
            $label = Form::label(null, [
                'for' => $attributes['id'],
                'class' => 'control-label',
            ]);
        } else {
            $labelText = null;
            if (is_string($labelOptions)) {
                $labelText = $labelOptions;
                $labelOptions = [];
            }
            if (!is_array($labelOptions)) {
                $labelOptions = [];
            }
            if (isset($labelOptions['text'])) {
                $labelText = $labelOptions['text'];
                unset($labelOptions['text']);
            }
            if (isset($labelOptions['tooltip'])) {
                $labelText .= '&nbsp;<i class="fa fa-info-circle text-info bs-tooltip nav-hint" title="' . HTML::chars($labelOptions['tooltip']) . '"></i>';
                unset($labelOptions['tooltip']);
            }
            if (!isset($labelOptions['for'])) {
                $labelOptions['for'] = $attributes['id'];
            }
            $classes = ['control-label'];
            if (isset($labelOptions['class'])) {
                if (is_array($labelOptions['class'])) {
                    $classes = array_merge($classes, $labelOptions['class']);
                } else {
                    $classes[] = $labelOptions['class'];
                }
            }
            $labelOptions['class'] = $classes;
            $label = Form::label($labelText, $labelOptions);
        }
        return $label;
    }

    public static function req(array $attributes = null)
    {
        if (!isset($attributes['name'])) {
            if (!isset($attributes['data-name'])) {
                if (!isset($attributes['id'])) {
                    $attributes['name'] = Text::random();
                } else {
                    $attributes['name'] = $attributes['id'];
                }
            } else {
                $attributes['name'] = $attributes['data-name'];
            }
        }
        if (!isset($attributes['data-name'])) {
            $attributes['data-name'] = $attributes['name'];
        }
        if (!isset($attributes['id'])) {
            $attributes['id'] = 'f_' . strtolower(preg_replace('/[^a-zA-Z_-]/', '', $attributes['name']));
        }
        $classes = ['form-control'];
        if (isset($attributes['class'])) {
            if (is_array($attributes['class'])) {
                $classes = array_merge($classes, $attributes['class']);
            } else {
                $classes[] = $attributes['class'];
            }
        }
        $attributes['class'] = $classes;
        return $attributes;
    }

}

