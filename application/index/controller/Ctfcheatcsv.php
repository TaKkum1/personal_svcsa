<?php

namespace app\index\controller;

use think\Db;
use think\Session;
use think\Response;

class CtfcheatCSV extends Base
{
    const FIELD = 'EventID,HeatID,LaneNumber,TeamName,TeamAbbr,ItemAgeGroupSex,Player1,Player2,Player3,Player4,Player5,Player6,Result,Note,IsSingle,HeatSize,ItemName,Gender,AgeGroupNumber,ItemPlayerID,TeamID,ItemID,Division';

    public function generateCSV()
    {
        // Retrieve data from the database
        $list = Db::name('ctfc_heat_view')
        ->order(['EventID', 'HeatID', 'LaneNumber'])
        ->select();

        // Modify the player1-6 fields to combine them into a single column
        $modifiedList = [];
        foreach ($list as $heateachrow) {
            $newTable = [];
            $newTable['D ( DO NOT CHANGE)'] ='D';
            $newTable['Last Name'] ='LN';
            $newTable['First Name'] ='FN';
            $newTable['LEAVE BLANK'] ='';
            // Check if the gender value is 'M' or 'F', otherwise set it to 'M'
            $gender = $heateachrow['Gender'];
            $gender = (strtoupper($gender) === 'M' || strtoupper($gender) === 'F') ? strtoupper($gender) : 'M';
            $newTable['Gender M or F'] =$gender;
            $newTable['Birthdate'] ='';
            $newTable['Club(Abbr)'] = $heateachrow['TeamAbbr'];
            $newTable['Club'] = $heateachrow['TeamName'];
            $newTable['Age'] ='';
            $newTable['Grade'] ='';
            // Strip any characters other than integers and 'x' from the 'Event' field
            $event = $heateachrow['ItemName'];
            $strippedEvent = preg_replace('/[^0-9x]/i', '', $event);
            // Format the EVENT value to align in the cell
            $formattedEvent = '="' . $strippedEvent . '"';
            $newTable['EVENT (only 1)'] = $formattedEvent;
            $newTable['SEED TIME'] ='';
            $newTable['M ( DO NOT CHANGE)'] ='M';
            // If gender is not 'M' or 'F', concatenate "mixed" with the 'Division' value
            $dgender = $heateachrow['Gender'];
            if ($dgender !== 'M' && $dgender !== 'F') {
                $newTable['DIVISION'] = trim($heateachrow['Division']) . ' mixed';
            } else {
                $newTable['DIVISION'] = trim($heateachrow['Division']);
            }
            // $newTable['ID'] = $heateachrow['ID'];
            // $newTable['EventID'] = $heateachrow['EventID'];
            // $newTable['HeatID'] = $heateachrow['HeatID'] ? $heateachrow['HeatID'] : 1;
            // $newTable['LaneNumber'] = $heateachrow['LaneNumber'];
            // $newTable['ItemAgeGroupSex'] = $heateachrow['ItemAgeGroupSex'];

            // $players = [];
            // for ($i = 1; $i <= 6; $i++) {
            //     if ($heateachrow["Player{$i}"]) {
            //         $players[] = $heateachrow["Player{$i}"];
            //     }
            // }
            // $newTable['Players'] = implode(', ', $players);
            // $newTable['Result'] = $heateachrow['Result'];
            // $newTable['Note'] = $heateachrow['Note'];
            

            $modifiedList[] = $newTable;
        }

        // Prepare the CSV file data
        $fp = fopen('php://temp', 'w');
        fputcsv($fp, array_keys($modifiedList[0])); // Column headers
        foreach ($modifiedList as $row) {
            fputcsv($fp, $row); // Data rows
        }

        rewind($fp);
        $csvData = stream_get_contents($fp);
        fclose($fp);

        // Set headers to prompt download
        $filename = 'ctfc_heat_view.csv';

        // Create a ThinkPHP response and output CSV data
        $response = new Response();
        $response->header([
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
            'Content-Type' => 'text/csv',
        ]);
        $response->data($csvData);
        return $response;
    }
}