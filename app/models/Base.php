<?php
namespace App\Models;

use Phalcon\Mvc\Model;

class Base extends Model
{
	public static $primary_key;
    public static $datatables_columns;
    public static $search_fields;
    public static $file_fields;
    public static $labels;

    public static function simpleDataArray($value_field = 'name', $lines = false, $zero_value = false)
    {
        $arr = [];
        if ($zero_value) $arr[0] = $zero_value;
        if (!$lines) $lines = self::find(['order' => $value_field])->toArray();
        $class_name = get_called_class();
        foreach ($lines as $line) {
            if (is_array($value_field)) {
                $val = [];
                foreach ($value_field as $field_name) {
                    $val[] = $line[$field_name];
                }
                $val = trim(implode(' ', $val));
            } else {
                $val = $line[$value_field];
            }
            $arr[$line[$class_name::$primary_key]] = $val;
        }
        return $arr;
    }

    public function checkDir($dir)
    {
        $path = $_SERVER['DOCUMENT_ROOT'] . $dir;
        if (!is_dir($path)) mkdir($path, 0755, true);
    }

    public function checkUploadDir($module_upload_dir)
    {
        $model_upload_dir = $module_upload_dir . '/' . mb_strtolower($this->getClearClass());
        $this->checkDir($model_upload_dir);
        return $model_upload_dir;
    }

    public function getClearClass()
    {
        $class_name = get_class($this);
        $path = explode('\\', $class_name);
        return $path[(count($path) - 1)];
    }

    public function getVal($field)
    {
        if (is_array($field)) return $this->getRelatedVal($field);
        return $this->getClearVal($field);
    }

    public function getClearVal($field)
    {
        return $this->$field;
    }

    public function getRelatedVal($field_data)
    {
        $cur_val = $this;
        $n = 0;
        foreach ($field_data as $field) {
            $n++;
            if ($n == count($field_data)) {
                $fields = explode('|', $field);
                $cur_vals = [];
                foreach ($fields as $field) {
                    $cur_vals[] = $cur_val->$field;
                }
                $cur_val = implode(' | ', $cur_vals);
            } else {
                $cur_val = $cur_val->$field;
            }
            if (empty($cur_val)) return '';
        }
        return $cur_val;
    }

    public static function selectOptions($field_name)
    {
        return [];
    }

}