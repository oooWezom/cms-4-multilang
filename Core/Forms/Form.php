<?php
namespace Forms;

use Core\HTML;

class Form
{

    /**
     * @var  boolean  use strict XHTML mode?
     */
    public static $strict = true;


    /**
     * @var  array  preferred order of attributes
     */
    public static $attribute_order = [
        'action',
        'method',
        'type',
        'id',
        'name',
        'value',
        'href',
        'src',
        'width',
        'height',
        'cols',
        'rows',
        'size',
        'maxlength',
        'rel',
        'media',
        'accept-charset',
        'accept',
        'tabindex',
        'accesskey',
        'alt',
        'title',
        'class',
        'style',
        'selected',
        'checked',
        'readonly',
        'disabled',
    ];

    public static $charset = 'UTF-8';

    /**
     * Generates an opening HTML form tag.
     *
     *     // Form will submit back to the current page using POST
     *     echo Form::open();
     *
     *     // Form will submit to 'search' using GET
     *     echo Form::open('search', array('method' => 'get'));
     *
     *     // When "file" inputs are present, you must include the "enctype"
     *     echo Form::open(NULL, array('enctype' => 'multipart/form-data'));
     *
     * @param   array $attributes html attributes
     * @return  string
     * @uses    Request
     * @uses    URL::site
     * @uses    HTML::attributes
     */
    public static function open(array $attributes = null)
    {
        if (isset($attributes['action'])) {
            $attributes['action'] = HTML::link($attributes['action']);
        }
        // Only accept the default character set
        $attributes['accept-charset'] = Form::$charset;
        if (!isset($attributes['method'])) {
            // Use POST method
            $attributes['method'] = 'post';
        }
        return '<form' . Form::attributes($attributes) . '>';
    }

    /**
     * Creates the closing form tag.
     *
     *     echo Form::close();
     *
     * @return  string
     */
    public static function close()
    {
        return '</form>';
    }


    /**
     * Creates a form input. If no type is specified, a "text" type input will
     * be returned.
     *
     *     echo Form::input('username', $username);
     *
     * @param   array $attributes html attributes
     * @return  string
     * @uses    Form::attributes
     * @throws \Exception
     */
    public static function input(array $attributes = null)
    {
        if (!isset($attributes['type'])) {
            // Default type is text
            $attributes['type'] = 'text';
        }
        return '<input' . Form::attributes($attributes) . ' />';
    }


    /**
     * Creates a hidden form input.
     *
     *     echo Form::hidden('csrf', $token);
     *
     * @param   array $attributes html attributes
     * @return  string
     * @uses    Form::input
     */
    public static function hidden(array $attributes = null)
    {
        $attributes['type'] = 'hidden';

        return Form::input($attributes);
    }


    /**
     * Creates a password form input.
     *
     *     echo Form::password('password');
     *
     * @param   array $attributes html attributes
     * @return  string
     * @uses    Form::input
     */
    public static function password(array $attributes = null)
    {
        $attributes['type'] = 'password';
        return Form::input($attributes);
    }


    /**
     * Creates a file upload form input. No input value can be specified.
     *
     *     echo Form::file('image');
     *
     * @param   array $attributes html attributes
     * @return  string
     * @uses    Form::input
     */
    public static function file(array $attributes = null)
    {
        $attributes['type'] = 'file';
        return Form::input($attributes);
    }


    /**
     * Creates a checkbox form input.
     *
     *     echo Form::checkbox('remember_me', 1, (bool) $remember);
     *
     * @param   bool $checked
     * @param   array $attributes html attributes
     * @return  string
     * @uses    Form::input
     */
    public static function checkbox($checked = false, array $attributes = null)
    {
        if ($checked) {
            $attributes[] = 'checked';
        }
        $attributes['type'] = 'checkbox';
        return Form::input($attributes);
    }


    /**
     * Creates a radio form input.
     *
     *     echo Form::radio('like_cats', 1, $cats);
     *     echo Form::radio('like_cats', 0, ! $cats);
     *
     * @param   bool $checked
     * @param   array $attributes html attributes
     * @return  string
     * @uses    Form::input
     */
    public static function radio($checked = false, array $attributes = null)
    {
        if ($checked) {
            $attributes[] = 'checked';
        }
        $attributes['type'] = 'radio';
        return Form::input($attributes);
    }


    /**
     * Creates a textarea form input.
     *
     *     echo Form::textarea('about', $about);
     *
     * @param   string $body textarea body
     * @param   array $attributes html attributes
     * @param   boolean $double_encode encode existing HTML characters
     * @return  string
     * @uses    Form::attributes
     * @uses    HTML::chars
     * @throws \Exception
     */
    public static function textarea($body = '', array $attributes = null, $double_encode = true)
    {
        // Add default rows and cols attributes (required)
        if (!isset($attributes['rows'])) {
            $attributes['rows'] = 10;
        }
        return '<textarea' . Form::attributes($attributes) . '>' . HTML::chars($body, $double_encode) . '</textarea>';
    }


    /**
     * Creates a select form input.
     *
     *     echo Form::select('country', $countries, $country);
     *
     * [!!] Support for multiple selected options was added in v3.0.7.
     *
     * @param   string $name input name
     * @param   array|string $options available options
     * @param   mixed $selected selected option string, or an array of selected options
     * @param   array $attributes html attributes
     * @return  string
     * @uses    Form::attributes
     * @throws \Exception
     */
    public static function select($options = null, $selected = null, array $attributes = null)
    {
        if (is_array($selected)) {
            // This is a multi-select, god save us!
            $attributes[] = 'multiple';
        }

        if (!is_array($selected)) {
            if ($selected === null) {
                // Use an empty array
                $selected = [];
            } else {
                // Convert the selected options to an array
                $selected = [(string)$selected];
            }
        }

        if (empty($options)) {
            // There are no options
            $options = '';
        } else if (is_array($options)) {
            foreach ($options as $value => $name) {
                if (is_array($name)) {
                    // Create a new optgroup
                    $group = ['label' => $value];

                    // Create a new list of options
                    $_options = [];

                    foreach ($name as $_value => $_name) {
                        // Force value to be string
                        $_value = (string)$_value;

                        // Create a new attribute set for this option
                        $option = ['value' => $_value];

                        if (in_array($_value, $selected)) {
                            // This option is selected
                            $option[] = 'selected';
                        }

                        // Change the option to the HTML string
                        $_options[] = '<option' . Form::attributes($option) . '>' . HTML::chars($_name, false) . '</option>';
                    }

                    // Compile the options into a string
                    $_options = "\n" . implode("\n", $_options) . "\n";

                    $options[$value] = '<optgroup' . Form::attributes($group) . '>' . $_options . '</optgroup>';
                } else {
                    // Force value to be string
                    $value = (string)$value;

                    // Create a new attribute set for this option
                    $option = ['value' => $value];

                    if (in_array($value, $selected)) {
                        // This option is selected
                        $option[] = 'selected';
                    }

                    // Change the option to the HTML string
                    $options[$value] = '<option' . Form::attributes($option) . '>' . HTML::chars($name, false) . '</option>';
                }
            }

            // Compile the options into a single string
            $options = "\n" . implode("\n", $options) . "\n";
        }

        return '<select' . Form::attributes($attributes) . '>' . $options . '</select>';
    }


    /**
     * Creates a submit form input.
     *
     *     echo Form::submit(NULL, 'Login');
     *
     * @param   array $attributes html attributes
     * @return  string
     * @uses    Form::input
     */
    public static function submit(array $attributes = null)
    {
        $attributes['type'] = 'submit';
        return Form::input($attributes);
    }


    /**
     * Creates a button form input. Note that the body of a button is NOT escaped,
     * to allow images and other HTML to be used.
     *
     *     echo Form::button('save', 'Save Profile', array('type' => 'submit'));
     *
     * @param   string $name input name
     * @param   string $body input value
     * @param   array $attributes html attributes
     * @return  string
     * @uses    Form::attributes
     * @throws \Exception
     */
    public static function button($body = null, array $attributes = null)
    {
        return '<button' . Form::attributes($attributes) . '>' . $body . '</button>';
    }


    /**
     * Compiles an array of HTML attributes into an attribute string.
     * Attributes will be sorted using HTML::$attribute_order for consistency.
     *
     *     echo '<div'.Form::attributes($attrs).'>'.$content.'</div>';
     *
     * @param   array $attributes attribute list
     * @return  string
     */
    public static function attributes(array $attributes = null)
    {
        if (empty($attributes))
            return '';

        $sorted = [];
        foreach (static::$attribute_order as $key) {
            if (isset($attributes[$key])) {
                // Add the attribute to the sorted list
                $sorted[$key] = $attributes[$key];
            }
        }

        // Combine the sorted attributes
        $attributes = $sorted + $attributes;

        $compiled = '';
        foreach ($attributes as $key => $value) {
            if ($value === null) {
                // Skip attributes that have NULL values
                continue;
            }

            if (is_int($key)) {
                // Assume non-associative keys are mirrored attributes
                $key = $value;

                if (!static::$strict) {
                    // Just use a key
                    $value = false;
                }
            }

            if (is_array($value)) {
                $value = array_unique($value);
                $value = implode(' ', $value);
            }

            // Add the attribute key
            $compiled .= ' ' . $key;

            if ($value or static::$strict) {
                // Add the attribute value
                $compiled .= '="' . HTML::chars($value) . '"';
            }
        }

        return $compiled;
    }


    /**
     * Creates a form label. Label text is not automatically translated.
     *
     *     echo Form::label('username', 'Username');
     *
     * @param   string $text label text
     * @param   array $attributes html attributes
     * @return  string
     * @uses    HTML::attributes
     */
    public static function label($text = null, array $attributes = null)
    {
        if ($text === null && isset($attributes['for'])) {
            // Use the input name as the text
            $text = ucwords(preg_replace('/[\W_]+/', ' ', $attributes['for']));
        }
        return '<label' . Form::attributes($attributes) . '>' . $text . '</label>';
    }

}