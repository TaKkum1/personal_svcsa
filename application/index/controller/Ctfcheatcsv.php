<?php

namespace app\index\controller;

use think\Db;
use think\Session;
use think\Response;

class CtfcheatCSV extends Base
{
    const FIELD = 'EventID,HeatID,LaneNumber,TeamName,TeamAbbr,ItemAgeGroupSex,Player1,Player2,Player3,Player4,Player5,Player6,Result,Note,IsSingle,IsTrack,HeatSize,ItemName,Gender,AgeGroupNumber,ItemPlayerID,TeamID,ItemID,Division';

    public function generateCSV()
    {
        // Retrieve data from the database
        $list = Db::name('ctfc_heat_view')
        ->where('IsTrack', 1)
        ->order(['EventID', 'HeatID', 'LaneNumber'])
        ->select();

        $modifiedList = [];
        foreach ($list as $heateachrow) {
            $newTable = [];
            $newTable['D ( DO NOT CHANGE)'] ='D';
            $lastNames = [];
            $firstNames = [];
            // Extract last name and first name from players' data and add them to the respective arrays
            if ($heateachrow["Player1"]) {
                $playerData = $heateachrow["Player1"];
                // Remove '#' character and any text after it from the player data
                $playerDataParts = explode('#', $playerData, 2);
                $playerData = $playerDataParts[0];
                // Check if the name contains any Chinese characters
                if (preg_match('/\p{Han}+/u', $playerData)) {
                    // If the name contains Chinese characters, use mb_substr to extract the first character as the last name
                    $lastName = mb_substr($playerData, 0, 1, 'UTF-8');
                    $firstName = mb_substr($playerData, 1, null, 'UTF-8');
                } else {
                    // If the name doesn't contain Chinese characters, assume the name is in Western order (First Last)
                    // Split the full name by the space character
                    $nameParts = explode(' ', $playerData, 2);
                    // First part is the first name
                    $firstName = trim($nameParts[0]);
                    // Second part is the last name
                    $lastName = isset($nameParts[1]) ? trim($nameParts[1]) : '';
                }
                // Add the names to the respective arrays
                $lastNames[] = $lastName;
                $firstNames[] = $firstName;
            }
            // Combine last names and first names into their respective columns
            $newTable['Last Name'] = implode(', ', $lastNames);
            $newTable['First Name'] = implode(', ', $firstNames);
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