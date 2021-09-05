<?php

declare(strict_types=1);

namespace Przeslijmi\Shortquery\Engine\MySql;

/**
 * Definitions for MySQL.
 */
class Defs
{
    
    /**
     * Set of character sets.
     *
     * @var array
     */
    const CHARACTER_SET = [
        'armscii8' => [
            'charSet' => 'armscii8',
            'description' => 'ARMSCII-8 Armenian',
            'defCollation' => 'armscii8_general_ci',
            'maxlength' => 1,
        ],
        'ascii' => [
            'charSet' => 'ascii',
            'description' => 'US ASCII',
            'defCollation' => 'ascii_general_ci',
            'maxlength' => 1,
        ],
        'big5' => [
            'charSet' => 'big5',
            'description' => 'Big5 Traditional Chinese',
            'defCollation' => 'big5_chinese_ci',
            'maxlength' => 2,
        ],
        'binary' => [
            'charSet' => 'binary',
            'description' => 'Binary pseudo character set',
            'defCollation' => 'binary',
            'maxlength' => 1,
        ],
        'cp1250' => [
            'charSet' => 'cp1250',
            'description' => 'Windows Central European',
            'defCollation' => 'cp1250_general_ci',
            'maxlength' => 1,
        ],
        'cp1251' => [
            'charSet' => 'cp1251',
            'description' => 'Windows Cyrillic',
            'defCollation' => 'cp1251_general_ci',
            'maxlength' => 1,
        ],
        'cp1256' => [
            'charSet' => 'cp1256',
            'description' => 'Windows Arabic',
            'defCollation' => 'cp1256_general_ci',
            'maxlength' => 1,
        ],
        'cp1257' => [
            'charSet' => 'cp1257',
            'description' => 'Windows Baltic',
            'defCollation' => 'cp1257_general_ci',
            'maxlength' => 1,
        ],
        'cp850' => [
            'charSet' => 'cp850',
            'description' => 'DOS West European',
            'defCollation' => 'cp850_general_ci',
            'maxlength' => 1,
        ],
        'cp852' => [
            'charSet' => 'cp852',
            'description' => 'DOS Central European',
            'defCollation' => 'cp852_general_ci',
            'maxlength' => 1,
        ],
        'cp866' => [
            'charSet' => 'cp866',
            'description' => 'DOS Russian',
            'defCollation' => 'cp866_general_ci',
            'maxlength' => 1,
        ],
        'cp932' => [
            'charSet' => 'cp932',
            'description' => 'SJIS for Windows Japanese',
            'defCollation' => 'cp932_japanese_ci',
            'maxlength' => 2,
        ],
        'dec8' => [
            'charSet' => 'dec8',
            'description' => 'DEC West European',
            'defCollation' => 'dec8_swedish_ci',
            'maxlength' => 1,
        ],
        'eucjpms' => [
            'charSet' => 'eucjpms',
            'description' => 'UJIS for Windows Japanese',
            'defCollation' => 'eucjpms_japanese_ci',
            'maxlength' => 3,
        ],
        'euckr' => [
            'charSet' => 'euckr',
            'description' => 'EUC-KR Korean',
            'defCollation' => 'euckr_korean_ci',
            'maxlength' => 2,
        ],
        'gb18030' => [
            'charSet' => 'gb18030',
            'description' => 'China National Standard GB18030',
            'defCollation' => 'gb18030_chinese_ci',
            'maxlength' => 4,
        ],
        'gb2312' => [
            'charSet' => 'gb2312',
            'description' => 'GB2312 Simplified Chinese',
            'defCollation' => 'gb2312_chinese_ci',
            'maxlength' => 2,
        ],
        'gbk' => [
            'charSet' => 'gbk',
            'description' => 'GBK Simplified Chinese',
            'defCollation' => 'gbk_chinese_ci',
            'maxlength' => 2,
        ],
        'geostd8' => [
            'charSet' => 'geostd8',
            'description' => 'GEOSTD8 Georgian',
            'defCollation' => 'geostd8_general_ci',
            'maxlength' => 1,
        ],
        'greek' => [
            'charSet' => 'greek',
            'description' => 'ISO 8859-7 Greek',
            'defCollation' => 'greek_general_ci',
            'maxlength' => 1,
        ],
        'hebrew' => [
            'charSet' => 'hebrew',
            'description' => 'ISO 8859-8 Hebrew',
            'defCollation' => 'hebrew_general_ci',
            'maxlength' => 1,
        ],
        'hp8' => [
            'charSet' => 'hp8',
            'description' => 'HP West European',
            'defCollation' => 'hp8_english_ci',
            'maxlength' => 1,
        ],
        'keybcs2' => [
            'charSet' => 'keybcs2',
            'description' => 'DOS Kamenicky Czech-Slovak',
            'defCollation' => 'keybcs2_general_ci',
            'maxlength' => 1,
        ],
        'koi8r' => [
            'charSet' => 'koi8r',
            'description' => 'KOI8-R Relcom Russian',
            'defCollation' => 'koi8r_general_ci',
            'maxlength' => 1,
        ],
        'koi8u' => [
            'charSet' => 'koi8u',
            'description' => 'KOI8-U Ukrainian',
            'defCollation' => 'koi8u_general_ci',
            'maxlength' => 1,
        ],
        'latin1' => [
            'charSet' => 'latin1',
            'description' => 'cp1252 West European',
            'defCollation' => 'latin1_swedish_ci',
            'maxlength' => 1,
        ],
        'latin2' => [
            'charSet' => 'latin2',
            'description' => 'ISO 8859-2 Central European',
            'defCollation' => 'latin2_general_ci',
            'maxlength' => 1,
        ],
        'latin5' => [
            'charSet' => 'latin5',
            'description' => 'ISO 8859-9 Turkish',
            'defCollation' => 'latin5_turkish_ci',
            'maxlength' => 1,
        ],
        'latin7' => [
            'charSet' => 'latin7',
            'description' => 'ISO 8859-13 Baltic',
            'defCollation' => 'latin7_general_ci',
            'maxlength' => 1,
        ],
        'macce' => [
            'charSet' => 'macce',
            'description' => 'Mac Central European',
            'defCollation' => 'macce_general_ci',
            'maxlength' => 1,
        ],
        'macroman' => [
            'charSet' => 'macroman',
            'description' => 'Mac West European',
            'defCollation' => 'macroman_general_ci',
            'maxlength' => 1,
        ],
        'sjis' => [
            'charSet' => 'sjis',
            'description' => 'Shift-JIS Japanese',
            'defCollation' => 'sjis_japanese_ci',
            'maxlength' => 2,
        ],
        'swe7' => [
            'charSet' => 'swe7',
            'description' => '7bit Swedish',
            'defCollation' => 'swe7_swedish_ci',
            'maxlength' => 1,
        ],
        'tis620' => [
            'charSet' => 'tis620',
            'description' => 'TIS620 Thai',
            'defCollation' => 'tis620_thai_ci',
            'maxlength' => 1,
        ],
        'ucs2' => [
            'charSet' => 'ucs2',
            'description' => 'UCS-2 Unicode',
            'defCollation' => 'ucs2_general_ci',
            'maxlength' => 2,
        ],
        'ujis' => [
            'charSet' => 'ujis',
            'description' => 'EUC-JP Japanese',
            'defCollation' => 'ujis_japanese_ci',
            'maxlength' => 3,
        ],
        'utf16' => [
            'charSet' => 'utf16',
            'description' => 'UTF-16 Unicode',
            'defCollation' => 'utf16_general_ci',
            'maxlength' => 4,
        ],
        'utf16le' => [
            'charSet' => 'utf16le',
            'description' => 'UTF-16LE Unicode',
            'defCollation' => 'utf16le_general_ci',
            'maxlength' => 4,
        ],
        'utf32' => [
            'charSet' => 'utf32',
            'description' => 'UTF-32 Unicode',
            'defCollation' => 'utf32_general_ci',
            'maxlength' => 4,
        ],
        'utf8' => [
            'charSet' => 'utf8',
            'description' => 'UTF-8 Unicode',
            'defCollation' => 'utf8_general_ci',
            'maxlength' => 3,
        ],
        'utf8mb4' => [
            'charSet' => 'utf8mb4',
            'description' => 'UTF-8 Unicode',
            'defCollation' => 'utf8mb4_0900_ai_ci',
            'maxlength' => 4,
        ],
    ];
}
