<?php
namespace App\Classes;

class Functions
{
    public function countSpell(int $count, string $count1, string $count24, string $count5):string
    {
        $num = substr((string)$count, strlen((string)$count) - 1);
        $num = (int)$num;
        if ($num === 1) {
            return $count1;
        }
        if ($num > 1 && $num < 5) {
            return $count24;
        }
        return $count5;
    }

	public function rus2Translit(string $string):string
    {
        $converter = [
            'а' => 'a',   'б' => 'b',   'в' => 'v',
            'г' => 'g',   'д' => 'd',   'е' => 'e',
            'ё' => 'e',   'ж' => 'zh',  'з' => 'z',
            'и' => 'i',   'й' => 'y',   'к' => 'k',
            'л' => 'l',   'м' => 'm',   'н' => 'n',
            'о' => 'o',   'п' => 'p',   'р' => 'r',
            'с' => 's',   'т' => 't',   'у' => 'u',
            'ф' => 'f',   'х' => 'h',   'ц' => 'c',
            'ч' => 'ch',  'ш' => 'sh',  'щ' => 'sch',
            'ь' => '\'',  'ы' => 'y',   'ъ' => '\'',
            'э' => 'e',   'ю' => 'yu',  'я' => 'ya',

            'А' => 'A',   'Б' => 'B',   'В' => 'V',
            'Г' => 'G',   'Д' => 'D',   'Е' => 'E',
            'Ё' => 'E',   'Ж' => 'Zh',  'З' => 'Z',
            'И' => 'I',   'Й' => 'Y',   'К' => 'K',
            'Л' => 'L',   'М' => 'M',   'Н' => 'N',
            'О' => 'O',   'П' => 'P',   'Р' => 'R',
            'С' => 'S',   'Т' => 'T',   'У' => 'U',
            'Ф' => 'F',   'Х' => 'H',   'Ц' => 'C',
            'Ч' => 'Ch',  'Ш' => 'Sh',  'Щ' => 'Sch',
            'Ь' => '\'',  'Ы' => 'Y',   'Ъ' => '\'',
            'Э' => 'E',   'Ю' => 'Yu',  'Я' => 'Ya',
            '®' => '',    '²' => '',    'ã' => '',
            '´' => '',    '&' => '',    'ö' => 'o'
        ];
        return strtr($string, $converter);
	}

	public function str2Url(string $str):string
    {
		// переводим в транслит
		$str = $this->rus2Translit($str);
		// в нижний регистр
		$str = strtolower($str);
		// заменям все ненужное нам на "-"
		$str = preg_replace('~[^-a-z0-9_]+~u', '-', $str);
		// удаляем начальные и конечные '-'
		$str = trim($str, '-');
        while (mb_strpos($str, '--') !== false) {
            $str = str_replace('--', '-', $str);
        }
		return $str;
	}

	public function guidV4():string
    {
		if (function_exists('com_create_guid') === true) {
            return trim(com_create_guid(), '{}');
        }

        $data = openssl_random_pseudo_bytes(16);
		$data[6] = chr(ord($data[6]) & 0x0f | 0x40); // set version to 0100
		$data[8] = chr(ord($data[8]) & 0x3f | 0x80); // set bits 6-7 to 10
		return strtoupper(vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($data), 4)));
	}

	public function randomAlias():string
    {
		$randomAlias = range('a', 'z');
		shuffle($randomAlias);
		return '-' . substr(implode('', $randomAlias), 0, 5);
	}
}