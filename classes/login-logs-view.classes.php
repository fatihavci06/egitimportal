<?php

include_once 'classes/dateformat.classes.php';

class ShowLogs extends LoginLogs
{

    public function getLogList()
    {
        $logsInfo = $this->getLogsList();

        $dateFormat = new DateFormat();

        foreach ($logsInfo as $key => $value):

            if ($value['logoutTime'] == null) {
                $logoutTime = '-';
            } else {
                $logoutTime =  $dateFormat->changeDateHour($value['logoutTime']);
            }

            $logList = '
             <tr>
                        <td>
                            ' . $value['name'] . ' ' . $value['surname'] . '
                        </td>
                         <td>
                            ' . $value['email'] . ' 
                        </td>
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
                        '  . $dateFormat->changeDateHour($value['loginTime']) . '
                        </td>
                        <td>
                        '  . $logoutTime . '
                        </td>
                       
                    </tr>
            ';

            echo $logList;

        endforeach;
    }
}
