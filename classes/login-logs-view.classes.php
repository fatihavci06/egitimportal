<?php

class ShowLogs extends LoginLogs
{

    public function getLogList()
    {
        $logsInfo = $this->getLogsList();

        foreach ($logsInfo as $key => $value):
            $logList = '
             <tr>
                        <td>
                            ' . $value['name'] . ' ' . $value['surname'] . '
                        </td>
                        <td>
                            ' . $value['deviceType'] . '
                        </td>
                        <td>
                            ' . $value['deviceModel'] . '
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
                        '  . $value['loginTime'] . '
                        </td>
                        <td>
                        '  . $value['logoutTime'] . '
                        </td>
                       
                    </tr>
            ';

            echo $logList;

        endforeach;
    }
}
