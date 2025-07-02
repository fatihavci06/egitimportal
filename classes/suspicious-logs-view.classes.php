<?php
include_once "dateformat.classes.php";

class ShowLogs extends SuspiciousLogs
{

    public function getSuspiciousLogList()
    {
        $logsInfo = $this->getSuspiciousLogsList();

        $dateFormat = new DateFormat();

        foreach ($logsInfo as $key => $value):
            $logList = '
             <tr>
                        <td>
                            ' . $value['deviceType'] . '
                        </td>
                        <td>
                            ' . $value['deviceOs'] . '
                        </td>
                        <td>
                           ' . $value['browser'] . '
                        </td>
                        <td>
                        '  . $value['resolution'] . '
                        </td>
                        <td>
                        '  . $value['ipAddress'] . '
                        </td>
                        <td>
                        '  . $dateFormat->changeDateHour($value['attempt_time']). '
                       </td>
                    </tr>
            ';

            echo $logList;

        endforeach;
    }
}
