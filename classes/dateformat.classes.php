<?php

class DateFormat
{

    public function changeDate($date)
    {
        $sqlDate = $date;
        $dateChange = strtotime($sqlDate);
        $result = date("d.m.Y", $dateChange);
        return $result;
    }

    public function changeDateHour($date)
    {
        $sqlDate = $date;
        $dateChange = strtotime($sqlDate);
        $result = date("d.m.Y H:i", $dateChange);
        return $result;
    }

    public function changeDateHourSecond($date)
    {
        $sqlDate = $date;
        $dateChange = strtotime($sqlDate);
        $result = date("Y-m-d H:i:s", $dateChange);
        return $result;
    }

    public function forDB($date)
    {
        $dateChange = strtotime(str_replace('.', '-', $date));
        $dbFormat = date('Y-m-d', $dateChange);
        return $dbFormat;
    }

    public function timeDifference($now, $created_at)
    {
        $createTime = new DateTime($created_at);
        $difference = $createTime->diff($now);

        $yil = $difference->y;
        $ay = $difference->m;
        $gun = $difference->d;
        $saat = $difference->h;
        $dakika = $difference->i;
        $saniye = $difference->s;

        $result = '';
        if ($yil > 0) {
            $result .= $yil . ' yıl ';
        }
        if ($ay > 0) {
            $result .= $ay . ' ay ';
        }
        if ($gun > 0) {
            $result .= $gun . ' gün ';
        }
        if ($saat > 0) {
            $result .= $saat . ' saat ';
        }
        /*if ($dakika > 0) {
            $result .= $dakika . ' dakika ';
        }
        if ($saniye > 0 || empty($result)) { // Eğer hiçbir şey yoksa saniyeyi göster
            $result .= $saniye . ' saniye ';
        }*/

        $result .= 'önce';

        echo $result;
    }
}
