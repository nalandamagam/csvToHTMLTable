<?php
/**
 * Created by PhpStorm.
 * User: nalanda
 * Date: 10/6/18
 * Time: 11:57 AM
 */

main::start("csvTestFile.csv");

class main {
    public static function start($csvFileName){
        $records = csv::getCSVRecords($csvFileName);
        $table = html::generateHTMLTable($records);
        echo $table;
    }
}

class csv {
    public static function getCSVRecords($csvFileName){
        $csvFile = fopen($csvFileName, "r");
        $columnNames = array();
        $isHeaderRecord = true;

        while(!feof($csvFile)){
            $row = fgetcsv($csvFile);
            if($isHeaderRecord){
                $columnNames = $row;
                $isHeaderRecord = false;
            } else {
                $records[] = recordFactory::createRecord($columnNames, $row);
            }
        }

        fclose($csvFile);
        return $records;
    }
}

class recordFactory {
    public static function createRecord(Array $columnNames = null, $cellValues = null){
        $record = new record($columnNames, $cellValues);
        return $record->returnRecord();
    }
}

class record {
    private $record = array();
    public function __construct(Array $columnNames = null, $cellValues = null) {
        $record = array_combine($columnNames, $cellValues);
        $this->record = $record;
    }

    public function returnRecord(){
        return $this->record;
    }
}

class html {
    public static function generateHTMLTable($records) {
        $isFirstRecord = true;
        $table = self::returnHTMLHeader();

        foreach ($records as $record) {
            $array = $record->returnRecordAsArray();
            if($isFirstRecord) {
                $fields = array_keys($array);
                $table = self::returnLoopString($fields, $table);
                $isFirstRecord = false;
            }
            $values = array_values($array);
            $table = self::returnLoopString($values, $table);
        }
        $table.='</table></body></html>';
        return $table;
    }

    public static function returnLoopString($array, $table){
        $table.='<tr>';
        foreach($array as $value){
            $table .= $value;
        }
        $table.= '</tr>';
        return $table;
    }
}

class utils {

    public static function addTableRow($tableRow){
        return '<tr>' . $tableRow . '</tr>';
    }

    public static function addTableTag($value, $isHeadersNeeded){
        if($isHeadersNeeded){
            return '<th>' . $value . '</th>';
        } else {
            return '<td>' . $value . '</td>';
        }
    }

    public static function returnHTMLHeader(){
        $table = '<!DOCTYPE html><html lang="en"><head><link rel="stylesheet" type="text/css" href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap.min.css" />
                    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
                    <script type="text/javascript" src="//maxcdn.bootstrapcdn.com/bootstrap/3.3.4/js/bootstrap.min.js"></script></head><body><table class="table table-bordered table-striped">';
        return $table;
    }
}