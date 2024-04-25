<?php
namespace App\Helpers;

class CalendarHelper
{
    public static function getTextFromStatus($status) {
        switch ($status) {
            case 0: return '未処理';
            case 1: return '処理中';
            case 2: return '処理済み';
            case 3: return '完了';
            default: return '';
        }
    }
}
?>