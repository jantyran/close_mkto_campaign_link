<?php 

	function reception( $date, $link, $is_closed ) {	
		($is_closed ? true : false) ? date("Y/m/d") > $date ? print "<td><span>受付終了</span></td>" : print "<td><a href={$link} target='_blank'>申込み</a></td>" : print "<td><span>受付終了</span></td>";
    }


    // [redirect] get handson recept table with the program id 
    function handsonReceptionTable ( $date, $time, $place, $organizer, $link, $maxNum, $programFullId, $is_closed ) {	
        $formLink;

        $programId = varidateEventProgramId ($programFullId);
        if ($programId == false ) {
            $is_closed = false;
        } 

        if ($is_closed) {
            if (date("Y/m/d") > $date) {
                $formLink = "<td><span>受付終了</span>";
            } else {
                $formLink = "<td><form method='POST' name={$programId} action='./redirect.php' target='_blank' style='margin: 0;'><input type='hidden' name='link' value={$link} ><input type='hidden' name='maxNum' value={$maxNum} ><input type='hidden' name='programId' value={$programId} ><input type='submit' value='申込み' target='_blank'></form></td>";
            }
        } else {
            $formLink = "<td><span>受付終了</span></td>";
        }

        print "<tr id='{$programId}' ><td>".getOmittedDate( $date )."</td><td>{$time}</td><td>{$place}</td><td>{$organizer}</td>{$formLink}</tr>";
    }

    

    // called in reception-part.php
    function redirect_to_form ($link, $maxNum, $programId) {

        // the url when the target event is fulled.
        $fulled_link = "/event/fulled-event.html";
        
        if (isNotFull($maxNum, $programId)) {
            // header("Location: $link");
             // instead of header()
             echo "
                <script type=\"text/javascript\">

                setTimeout(\"redirect()\", 0);

                function redirect() {
                    location.href=\"{$link}\";
                }

                </script>";

            exit;
        } else {
            // header("Location: $fulled_link");

            // instead of header()
            echo "
                <script type=\"text/javascript\">

                setTimeout(\"redirect()\", 0);

                function redirect() {
                    location.href=\"{$fulled_link}\";
                }

                </script>";

            exit;
        }
    }



    // Marketo API 
    class MarketoProgram{
        // input Marketo API info.
        private $host = "CHANGE ME";
        private $clientId = "CHANGE ME";
        private $clientSecret = "CHANGE ME";
        public $id;//id of campaign to retrieve


        public function countMembers() {
            $jsonData = $this->getData();
            $data = json_decode($jsonData, false);
            return count($data->result);
        }

        
        public function getData(){
            $url = $this->host . "/rest/v1/leads/programs/" . $this->id . ".json?access_token=" . $this->getToken();
            $ch = curl_init($url);
            curl_setopt($ch,  CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_HTTPHEADER, array('accept: application/json',));
            $response = curl_exec($ch);
            return $response;
        }
        
        private function getToken(){
            $ch = curl_init($this->host . "/identity/oauth/token?grant_type=client_credentials&client_id=" . $this->clientId . "&client_secret=" . $this->clientSecret);
            curl_setopt($ch,  CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_HTTPHEADER, array('accept: application/json',));
            $response = json_decode(curl_exec($ch));
            curl_close($ch);
            $token = $response->access_token;
            return $token;
        }
    }

    // check the allowanace of the event.
    function isNotFull($maxNum, $programId) { 
        $marketoProgram = new MarketoProgram();
        $marketoProgram->id = $programId;
        $memberNum = $marketoProgram->countMembers();

        return $maxNum > $memberNum ? true : false ;
    }

    function getFullDateWithYear( $date ) {
        $datetime = new DateTime($date);
        $weekList = array("日", "月", "火", "水", "木", "金", "土");
        $w = $weekList[$datetime->format('w')];
        $fulldate = date('Y年m月d日', strtotime($date)).'('.$w.')';

        return $fulldate;
    }

    function getOmittedDate( $date ) {
        $datetime = new DateTime($date);
        $weekList = array("日", "月", "火", "水", "木", "金", "土");
        $w = $weekList[$datetime->format('w')];

        $fulldate = date('n月j日', strtotime($date)).'（'.$w.'）';
        return $fulldate;
    }

    function varidateEventProgramId ($programFullId) {
        $isEventProgramId = strpos($programFullId, "#ME");
        $isEventProgramIdwithA = strpos($programFullId, "A1");

        if( $isEventProgramId === false || $isEventProgramIdwithA === false ) {
            echo "warning: something wrong with program id. <br>";
            echo "put a correct program id; start with '#ME' and end with 'A1'. <br> >";
            echo $programFullId;
            return false;
        }

        $explodedId = explode("E", $programFullId);
        $explodedId = explode( "A" ,$explodedId[1]);
        $programId = $explodedId[0];

        return $programId;
    }