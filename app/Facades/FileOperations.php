<?php


namespace App\Facades;


use App\Iptvusers;
use App\M3uData;
use Illuminate\Support\Facades\Storage;

class FileOperations
{
    /**
     * @param $file
     * @return false|resource
     */
    private function GetFileReader($file)
    {

        return fopen('../storage/app/'.$file,'r');
    }

    /**
     * @param $filename
     * @return array
     * convert files to array
     */
    private function TextFileToArray($filename)
    {
        $file=$this->GetFileReader($filename);
        $first = fgets($file);
        $data = array();
        $i=0;
        while (!feof($file))
        {
            $data[$i]=fgets($file);
            ++$i;
        }
        return $data;
    }

    /**
     * @param $filename
     * @return array
     * adjusting the lines in file
     */

    private function AdjustLinesM3uFile($filename)
    {
        $data = $this->TextFileToArray($filename);
        $temp = array();
        for($i=0,$j=0;$i<count($data)/2;$i++,$j+=2)
        {
            $temp[$i]=$data[$j].'|'.$data[$j+1];
        }
        return $temp;
    }

    /**
     * @param $filename
     * @return false|string
     * converts the m3u files to json objects
     */

    public function ConvertM3U(&$filename)
    {
        $file = $this->AdjustLinesM3uFile($filename);
        $list = array();
        $total_data = [];
        for ($i=0;$i<count($file);++$i){
            $data = new M3uData();
            $link_fetch = explode('|',$file[$i]);
            $data->channelLink=$link_fetch[1];
            $string = explode("\"", $link_fetch[0]);
            for ($j = 0; $j < count($string); ++$j) {

                if (strpos($string[$j], 'tvg-id')) {
                    $data->channelId=$string[$j+1];
                }
                if (strpos($string[$j], 'tvg-name')) {
                    $data->channelName=trim(str_replace('TR:','',$string[$j + 1]));
                }
                if (strpos($string[$j], 'tvg-logo')) {
                    $data->logo=$string[$j + 1];
                }
                if (strpos($string[$j], 'group-title')) {
                    $data->group=$string[$j + 1];
                }
            }
            array_push($total_data, $data);
        }
        return $total_data;
    }
    private function startsWith ($string, $startString)
    {
        $len = strlen($startString);
        return (substr($string, 0, $len) === $startString);
    }
// epg file readers
    public function get_xml($EpgFile) {
        $xml = simplexml_load_string(Storage::get($EpgFile));
        if(!$xml)
            throw new ParserException('Failed To Parse XML');
        return json_decode(json_encode($xml), true);   // Work arround to accept xml input
    }

    public function xml($Channel,Iptvusers $user){
        $EpgFile = $user->epgfile;
        $id =$Channel;  // this is channel id

        if ($id == null)
            return response()->json(['id' => $id , 'error' => 'Channel id can not be null'], 400);

        $object = $this->get_xml($EpgFile->efile);

        $channels = $object['channel'];
        $programmes = $object['programme'];

        $display_name = '';

        foreach ($channels as $channel) //  get channel name from channel id
        {
            if ($channel['@attributes']['id'] == $id){
                $display_name = $channel['display-name'];
                break;
            }
        }

        if ($display_name == null)
            return response()->json(['id' => $id , 'error' => $id.' not available in channel list'], 404);


        $var = [
            'id'   => (string)$id,
            'display_name'=> (string)$display_name,
            'programme' => [
                'current'=> [],
                'next'   => [],
                'today'  => [],
//                'total'  => [],
            ],
        ];


        $prev = null;
        $count = 0;
        foreach ($programmes as $programme){
            if ($programme['@attributes']['channel'] == $id){
//                date_default_timezone_set('');

                $start_temp = substr($programme['@attributes']['start'],0,12);  //get datetime string
                $stop_temp = substr($programme['@attributes']['stop'],0,12);


                $start  = \DateTime::createFromFormat('YmdHi', $start_temp);  //    convert datetime of programme from plain string to time_object
                $stop   = \DateTime::createFromFormat('YmdHi', $stop_temp);
                $now    = new \DateTime('now'); //get current time to compare date for today


                $date_format = 'Y/m/d';
                $time_format = 'h:i A';

                $diff = $start->diff($stop)->format('%H:%I');   // difference of programme start and stop time,  in hours:minutes format
//                echo $start->format('h:i A') . ' - ' . $stop->format('h:i A').' = '. $diff . '<br>';


                $title = $programme['title'];
                if (gettype($title) == 'array'){
                    $title = $title[0];
                }



                $temp = [
                    'title' => empty($programme['title'])?null:$title,
                    'desc' => empty($programme['desc'])?null:(string)$programme['desc'],
                    'clock' => [
                        'start' => [
                            'date'  => (string)$start->format($date_format),
                            'time'  => (string)$start->format($time_format)
                        ],
                        'stop'  => [
                            'date'  => (string)$stop->format($date_format),
                            'time'  => (string)$stop->format($time_format)
                        ],
                        'interval'  => (string)$diff
                    ],
                ];

                if($start > $now && $count<3)   // comparing programme date with today's date to get next programmes and current programme
                {
                    $count++;
                    array_push($var['programme']['next'], $temp);

                    if ($var['programme']['current'] == null)   //get the current programme when next programme is find then previous programme is current programme
                        array_push($var['programme']['current'], $prev);
                }
                $prev = $temp;

                if($now->format('d') == $start->format('d'))    // comparing programme date with today's date to get today's total programme
                    array_push($var['programme']['today'], $temp);

                //array_push($var['programme']['total'], $temp);    // getting total programme of channel id
            }
        }
        return response()->json($var);
    }
}
