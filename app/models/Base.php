<?php
namespace App\Models;

use App\Classes\UnlinkException;
use Phalcon\Di;
use Phalcon\Mvc\Model;

class Base extends Model
{
    public static $dataTablesColumns;
    public static $searchFields;
    public static $fileFields;
    public static $labels;
    public static $dbSchema;

    public function initialize()
    {
        $config = Di::getDefault()->get('config');
        if ($config->db_adapter == 'Postgresql') {
            if (!empty(static::$dbSchema)) {
                $this->setSchema(static::$dbSchema);
            } else {
                $this->setSchema($config->db_schema);
            }
        }

    }

    public static function simpleDataArray(string $keyField, array $valueField = ['name'], array $lines = [], string $zeroValue = null):array
    {
        $arr = [];
        if ($zeroValue) $arr[0] = $zeroValue;
        if (empty($lines)) {
            $params = (count($valueField) == 1) ? ['order' => $valueField[0]] : null;
            $lines = self::find($params)->toArray();
        }
        foreach ($lines as $line) {
            if (count($valueField) > 1) {
                $val = [];
                foreach ($valueField as $fieldName) {
                    $val[] = $line[$fieldName];
                }
                $val = trim(implode(' ', $val));
            } else {
                $val = $line[$valueField[0]];
            }
            $arr[$line[$keyField]] = $val;
        }
        return $arr;
    }

    public function checkDir(string $dir):void
    {
        $path = $_SERVER['DOCUMENT_ROOT'] . $dir;
        if (!is_dir($path)) mkdir($path, 0755, true);
    }

    public function checkUploadDir(string $moduleUploadDir):string
    {
        $modelUploadDir = $moduleUploadDir . '/' . mb_strtolower($this->getClearClass());
        $this->checkDir($modelUploadDir);
        return $modelUploadDir;
    }

    public function getClearClass():string
    {
        $className = get_class($this);
        $path = explode('\\', $className);
        return $path[(count($path) - 1)];
    }

    public function getVal(array $field)
    {
        if (count($field) > 1) return $this->getRelatedVal($field);
        return $this->getClearVal($field[0]);
    }

    public function getClearVal(string $field)
    {
        return $this->$field;
    }

    public function getRelatedVal(array $fieldData)
    {
        $curValue = $this;
        $n = 0;
        foreach ($fieldData as $fieldElement) {
            $n++;
            if ($n == count($fieldData)) {
                $fields = explode('|', $fieldElement);
                $curValues = [];
                foreach ($fields as $field) {
                    $curValues[] = $curValue->$field;
                }
                $curValue = implode(' | ', $curValues);
            } else {
                $curValue = $curValue->$fieldElement;
            }
            if (empty($curValue)) return '';
        }
        return $curValue;
    }

    public static function selectOptions(string $fieldName, array $params = []):array
    {
        return [];
    }

    public function deleteFile(string $field):void
    {
        if (!empty($this->$field)) {
            $fullPath = $_SERVER['DOCUMENT_ROOT'] . $this->$field;
            if (!unlink($fullPath)) {
                throw new UnlinkException('Cannot delete file: ' . $fullPath);
            }
            $this->$field = null;
            $this->update();
        }
    }

    public function deleteFiles():void
    {
        if (!empty(static::$fileFields)) {
            foreach (static::$fileFields as $fileField) {
                $this->deleteFile($fileField);
            }
        }
    }

    public function beforeDelete():bool
    {
        $this->deleteFiles();
        return true;
    }

}