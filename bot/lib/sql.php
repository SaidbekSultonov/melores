<?php 

// ==================================================================== SQL CLASS
    
    class ActionWithSql
    {   
        public $conn;
        protected $name;
        protected $chat_id;
        protected $surname;
        protected $username;
        protected $table_name;
        protected $updatePart;
        protected $wherePart;
        protected $whichSelect;
        protected $valuesPart;
        protected $ColumnsPart;
        protected $step_1 = 0;
        protected $step_2 = 0;
        protected $callback_data;
        protected $defoultWhere; 
        protected $nextWord;
        protected $prevWord;
        protected $updateKeyboard;
        protected $deleteKeyboard;
        protected $stopAnotherWork;
        public $dataExt;
        public $txtExt;

        public function __construct() {
            $servername = "localhost";
            $username = "artzoneu_bot";
            $dbname = "artzoneu_bot";
            $password = "4EBAafYDA[6B";

            $this->conn = mysqli_connect($servername, $username, $password, $dbname);
        }
     
        public function giveStep ($chat_id) {
            $sql = "SELECT * FROM step WHERE chat_id = ".$chat_id;
            $result = $this->conn->query($sql);
            if ($result->num_rows > 0) {
                $row = $result->fetch_assoc();
                $this->step_1 = $row["step_1"];
                $this->step_2 = $row["step_2"];

            }
            return "$this->step_1\n$this->step_2"; 
        }

        public function setStopAnotherWork($data,$text) {
            $this->stopAnotherWork = "Stop";
            $this->dataExt = $data;
            $this->txtExt = $text;
        }
        
        public function SetTable($table_name) {
            $this->table_name = $table_name;
        }

        public function setClData($clData) {
            $this->callback_data = $clData;
        }

        public function setNextBack($next,$back) {
            $this->nextWord = $next;
            $this->prevWord = $back;
        }

        public function setUpdateDelete($updateKeyboard,$deleteKeyboard) {
            $this->updateKeyboard = $updateKeyboard;
            $this->deleteKeyboard = $deleteKeyboard;
        }

        public function setDefWhere($defWhere) {
            $this->defoultWhere = $defWhere;
        }

        public function update($updatePart,$wherePart) {
            $this->updatePart = $updatePart;
            $this->wherePart = $wherePart;
            if (!empty($this->wherePart)) {
                $sql = "UPDATE ".$this->table_name." SET " . $this->updatePart. " WHERE " . $this->wherePart; 
                // return $sql; 
                return $this->conn->query($sql);
            } else {
                
            }
        }

        public function updateStep($updatePart,$wherePart) {
            $this->updatePart = $updatePart;
            $this->wherePart = $wherePart;
            if (!empty($this->wherePart)) {
                 $sql = "UPDATE step SET " . $this->updatePart. " WHERE " . $this->wherePart; 
                return $result = $this->conn->query($sql);
            } else {
                
            }
        }

        public function select($whichSelect,$wherePart) {
            $this->whichSelect = $whichSelect;
            $this->wherePart = $wherePart;
            if (!empty($this->wherePart)) {
                    $sql = "SELECT ".$this->whichSelect." FROM " . $this->table_name ." WHERE " . $this->wherePart; 
                    return $result = $this->conn->query($sql);
            } else {
                $sql = "SELECT ".$this->whichSelect." FROM " . $this->table_name; 
                return $result = $this->conn->query($sql);
            }
        }

        public function ClickBtnInlineNext($whichSelect,$wherePart) {
            $this->whichSelect = $whichSelect;
            $this->wherePart = $wherePart;
            if (!empty($this->wherePart)) {
                $sql = "SELECT ".$this->whichSelect." FROM " . $this->table_name ." WHERE " . $this->wherePart; 
                // return $sql;
                $result = $this->conn->query($sql);
                if ($result->num_rows > 0) {
                    $menu = [];
                    $b = 1;
                    $arr_Row= [];
                    while ($row = $result->fetch_assoc()) {
                        if ($b == 1) {
                            $prevData = $row["id"]."$this->prevWord";
                        }
                        $id = $row["id"];
                        $updateClDt = $id."_".$this->updateKeyboard;
                        $deleteClDt = $id."_".$this->deleteKeyboard;
                        $texts_category = "Dissconnect";
                        if ($this->step_1 == 1) {
                            $texts_category = base64_decode($row["title_uz"]);
                            $nextText = "Keyingi ➡️";
                            $prevText = "⬅️ Oldingi";
                            $homeText = "🏘 Bosh Menu";    
                            $backText = "🔙 Ortga";
                            $anotherWorkType = "Boshq ish turi";
                        } else if($this->step_1 == 2){
                            $texts_category = base64_decode($row["title_ru"]);
                            $nextText = "следующий ➡️";
                            $prevText = "⬅️ Предыдущий ";
                            $backText = "🔙 Назад";
                            $homeText = "🏘 Главное меню";
                            $anotherWorkType = "Другой вид работы";
                        } else if($this->step_1 == 3){
                            $texts_category = base64_decode($row["title_uz"]);
                            $nextText = "Кейинги ➡️";
                            $prevText = "⬅️ Олдинги";
                            $homeText = "🏘 Бош Мену";    
                            $backText = "🔙 Ортга";
                            $anotherWorkType = "Бошқ иш тури";
                        }
                        
                        if (empty($this->updateKeyboard) or empty($this->deleteKeyboard)){
                            $callback_id = $row['id']."$this->callback_data";
                            $menu[] =  ['callback_data' => "$callback_id",'text'=> "$texts_category"];

                            
                            $arr_Row[] = $menu;
                            $menu = [];
                            
                        } else if (!empty($this->updateKeyboard) and !empty($this->deleteKeyboard)) {
                            $callback_id = $row['id']."$this->callback_data";
                            $menu[] =  ['callback_data' => "$callback_id",'text'=> "$texts_category"];
                            $arr_Row[] = $menu;
                            $menu = [];
                            $update[] = ["callback_data" => $updateClDt,"text" => "🔄 O`zgartirish"];
                            $update[] = ["callback_data" => $deleteClDt,"text" => "❌ O`chirish"];
                            $arr_Row[] = $update;
                            $update = [];
                        }
                        $b++;        
                    }
                    $nextData = $id."$this->nextWord";
                   
                    if(count($menu) == 1)
                        $arr_Row[] = $menu;

                    if (empty($this->stopAnotherWork)) {
                        $arr_Row[] =  [['callback_data' => "anotherWork",'text'=> "$anotherWorkType"]];
                    } else if(!empty($this->stopAnotherWork)) {
                        $arr_Row[] =  [['callback_data' => $this->dataExt,'text'=> $this->txtExt]];
                    }

                    $arr_Row[] =  [['callback_data' => $prevData,'text'=> "$prevText"],['callback_data' => $nextData,'text'=> "$nextText"],];
                    $arr_Row[] =  [['callback_data' => "back",'text'=> "$backText"],['callback_data' => "home",'text'=> $homeText]];

                } else {
                    if (!empty($this->defoultWhere)) {
                        $sql = "SELECT * FROM $this->table_name WHERE $this->defoultWhere "; 
                    } else {
                        $sql = "SELECT * FROM $this->table_name WHERE status = 1 limit 10 "; 
                    }
                    $result = $this->conn->query($sql);
                    if ($result->num_rows > 0) {
                        $menu = [];
                        $b = 1;
                        $arr_Row= [];
                        while ($row = $result->fetch_assoc()) {
                            if ($b == 1) {
                                $prevData = $row["id"]."$this->prevWord";
                            }
                            $id = $row["id"];
                            $updateClDt = $id."_".$this->updateKeyboard;
                            $deleteClDt = $id."_".$this->deleteKeyboard;
                            $texts_category = "Dissconnect";
                           if ($this->step_1 == 1) {
                                $texts_category = base64_decode($row["title_uz"]);
                                $nextText = "Keyingi ➡️";
                                $prevText = "⬅️ Oldingi";
                                $homeText = "🏘 Bosh Menu";    
                                $backText = "🔙 Ortga";
                                $anotherWorkType = "Boshq ish turi";
                            } else if($this->step_1 == 2){
                                $texts_category = base64_decode($row["title_ru"]);
                                $nextText = "следующий ➡️";
                                $prevText = "⬅️ Предыдущий ";
                                $backText = "🔙 Назад";
                                $homeText = "🏘 Главное меню";
                                $anotherWorkType = "Другой вид работы";
                            } else if($this->step_1 == 3){
                                $texts_category = base64_decode($row["title_uz"]);
                                $nextText = "Кейинги ➡️";
                                $prevText = "⬅️ Олдинги";
                                $homeText = "🏘 Бош Мену";    
                                $backText = "🔙 Ортга";
                                $anotherWorkType = "Бошқ иш тури";
                            }

                            if (empty($this->updateKeyboard) or empty($this->deleteKeyboard)){
                                $callback_id = $row['id']."$this->callback_data";
                                $menu[] =  ['callback_data' => "$callback_id",'text'=> "$texts_category"];

                                
                                $arr_Row[] = $menu;
                                $menu = [];
                                
                            } else if (!empty($this->updateKeyboard) and !empty($this->deleteKeyboard)) {
                                $callback_id = $row['id']."$this->callback_data";
                                $menu[] =  ['callback_data' => "$callback_id",'text'=> "$texts_category"];
                                $arr_Row[] = $menu;
                                $menu = [];
                                $update[] = ["callback_data" => $updateClDt,"text" => "🔄 O`zgartirish"];
                                $update[] = ["callback_data" => $deleteClDt,"text" => "❌ O`chirish"];
                                $arr_Row[] = $update;
                                $update = [];
                            }  
                        $b++;        
                        }
                        $nextData = $id."$this->nextWord";
                       
                        if(count($menu) == 1)
                            $arr_Row[] = $menu;
                        
                        if (empty($this->stopAnotherWork)) {
                            $arr_Row[] =  [['callback_data' => "anotherWork",'text'=> "$anotherWorkType"]];
                        } else if(!empty($this->stopAnotherWork)) {
                            $arr_Row[] =  [['callback_data' => $this->dataExt,'text'=> $this->txtExt]];
                        }

                        $arr_Row[] =  [['callback_data' => $prevData,'text'=> "$prevText"],['callback_data' => $nextData,'text'=> "$nextText"],];
                        $arr_Row[] =  [['callback_data' => "back",'text'=> "$backText"],['callback_data' => "home",'text'=> $homeText],];
                    }
                }
                return $menu = json_encode(['inline_keyboard' => $arr_Row]);
            } else {
                $sql = "SELECT ".$this->whichSelect." FROM " . $this->table_name; 
            }
        }

        public function ClickBtnInlineNextUser($whichSelect,$wherePart) {
            $this->whichSelect = $whichSelect;
            $this->wherePart = $wherePart;
            if (!empty($this->wherePart)) {
                $sql = "SELECT ".$this->whichSelect." FROM " . $this->table_name ." WHERE " . $this->wherePart; 
                // return $sql;
                $result = $this->conn->query($sql);
                if ($result->num_rows > 0) {
                    $menu = [];
                    $b = 1;
                    $arr_Row= [];
                    while ($row = $result->fetch_assoc()) {
                        if ($b == 1) {
                            $prevData = $row["id"]."$this->prevWord";
                        }
                        $id = $row["id"];
                        $updateClDt = $id."_".$this->updateKeyboard;
                        $deleteClDt = $id."_".$this->deleteKeyboard;
                        $texts_category = base64_decode($row["full_name"]);
                        $nextText = "Keyingi ➡️";
                        $prevText = "⬅️ Oldingi";
                        $homeText = "🏘 Bosh Menu";    
                        $backText = "🔙 Ortga";
                        
                        if (empty($this->updateKeyboard) or empty($this->deleteKeyboard)){
                            $callback_id = $row['id']."$this->callback_data";
                            $menu[] =  ['callback_data' => "$callback_id",'text'=> "$texts_category"];

                            
                            $arr_Row[] = $menu;
                            $menu = [];
                            
                        } else if (!empty($this->updateKeyboard) and !empty($this->deleteKeyboard)) {
                            $callback_id = $row['id']."$this->callback_data";
                            $menu[] =  ['callback_data' => "$callback_id",'text'=> "$texts_category"];
                            $arr_Row[] = $menu;
                            $menu = [];
                            // $update[] = ["callback_data" => $updateClDt,"text" => "🔄 O`zgartirish"];
                            $update[] = ["callback_data" => $deleteClDt,"text" => "❌ O`chirish"];
                            $arr_Row[] = $update;
                            $update = [];
                        }
                    $b++;        
                    }
                    $nextData = $id."$this->nextWord";
                   
                    if(count($menu) == 1)
                        $arr_Row[] = $menu;

                    $arr_Row[] =  [['callback_data' => $prevData,'text'=> "$prevText"],['callback_data' => $nextData,'text'=> "$nextText"],];
                    $arr_Row[] =  [['callback_data' => "back",'text'=> "$backText"],['callback_data' => "home",'text'=> $homeText]];

                } else {
                    if (!empty($this->defoultWhere)) {
                        $sql = "SELECT * FROM $this->table_name WHERE $this->defoultWhere "; 
                    } else {
                        $sql = "SELECT * FROM $this->table_name WHERE status = 1 limit 10 "; 
                    }
                    $result = $this->conn->query($sql);
                    if ($result->num_rows > 0) {
                        $menu = [];
                        $b = 1;
                        $arr_Row= [];
                        while ($row = $result->fetch_assoc()) {
                            if ($b == 1) {
                                $prevData = $row["id"]."$this->prevWord";
                            }
                            $id = $row["id"];
                            $updateClDt = $id."_".$this->updateKeyboard;
                            $deleteClDt = $id."_".$this->deleteKeyboard;
                            $texts_category = base64_decode($row["full_name"]);
                            $nextText = "Keyingi ➡️";
                            $prevText = "⬅️ Oldingi";
                            $homeText = "🏘 Bosh Menu";    
                            $backText = "🔙 Ortga";

                            if (empty($this->updateKeyboard) or empty($this->deleteKeyboard)){
                                $callback_id = $row['id']."$this->callback_data";
                                $menu[] =  ['callback_data' => "$callback_id",'text'=> "$texts_category"];

                                
                                $arr_Row[] = $menu;
                                $menu = [];
                                
                            } else if (!empty($this->updateKeyboard) and !empty($this->deleteKeyboard)) {
                                $callback_id = $row['id']."$this->callback_data";
                                $menu[] =  ['callback_data' => "$callback_id",'text'=> "$texts_category"];
                                $arr_Row[] = $menu;
                                $menu = [];
                                // $update[] = ["callback_data" => $updateClDt,"text" => "🔄 O`zgartirish"];
                                $update[] = ["callback_data" => $deleteClDt,"text" => "❌ O`chirish"];
                                $arr_Row[] = $update;
                                $update = [];
                            }  
                        $b++;        
                        }
                        $nextData = $id."$this->nextWord";
                       
                        if(count($menu) == 1)
                            $arr_Row[] = $menu;

                        $arr_Row[] =  [['callback_data' => $prevData,'text'=> "$prevText"],['callback_data' => $nextData,'text'=> "$nextText"],];
                        $arr_Row[] =  [['callback_data' => "back",'text'=> "$backText"],['callback_data' => "home",'text'=> $homeText],];
                    }
                }
                return $menu = json_encode(['inline_keyboard' => $arr_Row]);
            } else {
                $sql = "SELECT ".$this->whichSelect." FROM " . $this->table_name; 
            }
        }

        public function selectBtnInline($whichSelect,$wherePart) {
            $this->whichSelect = $whichSelect;
            $this->wherePart = $wherePart;
            if (!empty($this->wherePart)) {
                $sql = "SELECT ".$this->whichSelect." FROM " . $this->table_name ." WHERE " . $this->wherePart; 
                // return $sql;
                $result = $this->conn->query($sql);
                if ($result->num_rows > 0) {
                    $menu = [];
                    $b = 1;
                    $arr_Row= [];
                    while ($row = $result->fetch_assoc()) {
                        $id = $row["id"];
                        $updateClDt = $id."_".$this->updateKeyboard;
                        $deleteClDt = $id."_".$this->deleteKeyboard;
                        $texts_category = "Dissconnect";
                        if ($this->step_1 == 1) {
                            $texts_category = base64_decode($row["title_uz"]);
                            $nextText = "Keyingi ➡️";
                            $prevText = "⬅️ Oldingi";
                            $homeText = "🏘 Bosh Menu";    
                            $backText = "🔙 Ortga";
                            $anotherWorkType = "Boshq ish turi";
                        } else if($this->step_1 == 2){
                            $texts_category = base64_decode($row["title_ru"]);
                            $nextText = "следующий ➡️";
                            $prevText = "⬅️ Предыдущий ";
                            $backText = "🔙 Назад";
                            $homeText = "🏘 Главное меню";
                            $anotherWorkType = "Другой вид работы";
                        } else if($this->step_1 == 3){
                            $texts_category = base64_decode($row["title_cr"]);
                            $nextText = "Кейинги ➡️";
                            $prevText = "⬅️ Олдинги";
                            $homeText = "🏘 Бош Мену";    
                            $backText = "🔙 Ортга";
                            $anotherWorkType = "Бошқ иш тури";
                        }
                        if (empty($this->updateKeyboard) or empty($this->deleteKeyboard)){
                            $callback_id = $row['id']."$this->callback_data";
                            $menu[] =  ['callback_data' => "$callback_id",'text'=> "$texts_category"];

                            
                            $arr_Row[] = $menu;
                            $menu = [];
                            
                        } else if (!empty($this->updateKeyboard) and !empty($this->deleteKeyboard)) {
                            $callback_id = $row['id']."$this->callback_data";
                            $menu[] =  ['callback_data' => "$callback_id",'text'=> "$texts_category"];
                            $arr_Row[] = $menu;
                            $menu = [];
                            $update[] = ["callback_data" => $updateClDt,"text" => "🔄 O`zgartirish"];
                            $update[] = ["callback_data" => $deleteClDt,"text" => "❌ O`chirish"];
                            $arr_Row[] = $update;
                            $update = [];
                        }
                    $b++;        
                    }
                    $nextData = $id."$this->nextWord";
                   
                    if(count($menu) == 1)
                        $arr_Row[] = $menu;

                    if (empty($this->stopAnotherWork)) {
                        $arr_Row[] =  [['callback_data' => "anotherWork",'text'=> "$anotherWorkType"]];
                    } else if(!empty($this->stopAnotherWork)) {
                        $arr_Row[] =  [['callback_data' => $this->dataExt,'text' =>$this->txtExt]];
                    }
                    if ($result->num_rows > 4) {
                        $arr_Row[] =  [['callback_data' => $nextData,'text'=> "$nextText"]];
                    }
                    $arr_Row[] =  [['callback_data' => "back",'text'=> "$backText"],['callback_data' => "home",'text'=> $homeText]];

                } else {
                    $bl = FALSE;
                    return $bl;
                }
                return $menu = json_encode(['inline_keyboard' => $arr_Row]);
            } else {
                $sql = "SELECT ".$this->whichSelect." FROM " . $this->table_name; 
            }
        }

        public function selectsBtnInlineWithBackHome($whichSelect,$wherePart) {
            $this->whichSelect = $whichSelect;
            $this->wherePart = $wherePart;
            if (!empty($this->wherePart)) {
                $sql = "SELECT ".$this->whichSelect." FROM " . $this->table_name ." WHERE " . $this->wherePart; 
                // return $sql;
                $result = $this->conn->query($sql);
                if ($result->num_rows > 0) {
                    $menu = [];
                    $b = 1;
                    $arr_Row= [];
                    while ($row = $result->fetch_assoc()) {
                        $id = $row["id"];
                        $updateClDt = $id."_".$this->updateKeyboard;
                        $deleteClDt = $id."_".$this->deleteKeyboard;
                        $texts_category = "Dissconnect";
                        if ($this->step_1 == 1) {
                            $texts_category = base64_decode($row["title_uz"]);
                            $nextText = "Keyingi ➡️";
                            $prevText = "⬅️ Oldingi";
                            $homeText = "🏘 Bosh Menu";    
                            $backText = "🔙 Ortga";
                            $anotherWorkType = "Boshq ish turi";
                        } else if($this->step_1 == 2){
                            $texts_category = base64_decode($row["title_ru"]);
                            $nextText = "следующий ➡️";
                            $prevText = "⬅️ Предыдущий ";
                            $backText = "🔙 Назад";
                            $homeText = "🏘 Главное меню";
                            $anotherWorkType = "Другой вид работы";
                        } else if($this->step_1 == 3){
                            $texts_category = base64_decode($row["title_cr"]);
                            $nextText = "Кейинги ➡️";
                            $prevText = "⬅️ Олдинги";
                            $homeText = "🏘 Бош Мену";    
                            $backText = "🔙 Ортга";
                            $anotherWorkType = "Бошқ иш тури";
                        }
                        if (empty($this->updateKeyboard) or empty($this->deleteKeyboard)){
                            $callback_id = $row['id']."$this->callback_data";
                            $menu[] =  ['callback_data' => "$callback_id",'text'=> "$texts_category"];

                            
                            $arr_Row[] = $menu;
                            $menu = [];
                            
                        } else if (!empty($this->updateKeyboard) and !empty($this->deleteKeyboard)) {
                            $callback_id = $row['id']."$this->callback_data";
                            $menu[] =  ['callback_data' => "$callback_id",'text'=> "$texts_category"];
                            $arr_Row[] = $menu;
                            $menu = [];
                            $update[] = ["callback_data" => $updateClDt,"text" => "🔄 O`zgartirish"];
                            $update[] = ["callback_data" => $deleteClDt,"text" => "❌ O`chirish"];
                            $arr_Row[] = $update;
                            $update = [];
                        }
                    $b++;        
                    }
                    $nextData = $id."$this->nextWord";
                   
                    if(count($menu) == 1)
                        $arr_Row[] = $menu;

                    $arr_Row[] =  [['callback_data' => $nextData,'text'=> "$nextText"]];
                    $arr_Row[] =  [['callback_data' => "back",'text'=> "$backText"],['callback_data' => "home",'text'=> $homeText]];
                    // $arr_Row[] = [];

                } else {
                    $bl = FALSE;
                    return $bl;
                }
                return $menu = json_encode(['inline_keyboard' => $arr_Row]);
            } else {
                $sql = "SELECT ".$this->whichSelect." FROM " . $this->table_name; 
            }
        }
     
        public function selectsBtnInlineUsers($whichSelect,$wherePart) {
            $this->whichSelect = $whichSelect;
            $this->wherePart = $wherePart;
            if (!empty($this->wherePart)) {
                $sql = "SELECT ".$this->whichSelect." FROM " . $this->table_name ." WHERE " . $this->wherePart; 
                $result = $this->conn->query($sql);
                if ($result->num_rows > 0) {
                    $menu = [];
                    $b = 1;
                    $arr_Row= [];
                    while ($row = $result->fetch_assoc()) {
                        $id = $row["id"];
                        $updateClDt = $id."_".$this->updateKeyboard;
                        $deleteClDt = $id."_".$this->deleteKeyboard;
                        $texts_category = base64_decode($row["full_name"]);
                        $nextText = "Keyingi ➡️";
                        $homeText = "🏘 Bosh Menu";    
                        $backText = "🔙 Ortga";
                        $goText = "Oldinga 🔜";
                        if (empty($this->updateKeyboard) or empty($this->deleteKeyboard)){
                            $callback_id = $row['id']."$this->callback_data";
                            $menu[] =  ['callback_data' => "$callback_id",'text'=> "$texts_category"];

                            
                            $arr_Row[] = $menu;
                            $menu = [];
                            
                        } else if (!empty($this->updateKeyboard) and !empty($this->deleteKeyboard)) {
                            $callback_id = $row['id']."$this->callback_data";
                            $menu[] =  ['callback_data' => "$callback_id",'text'=> "$texts_category"];
                            $arr_Row[] = $menu;
                            $menu = [];
                            $update[] = ["callback_data" => $deleteClDt,"text" => "❌ O`chirish"];
                            $arr_Row[] = $update;
                            $update = [];
                        }
                    $b++;        
                    }
                    $nextData = $id."$this->nextWord";
                   
                    if(count($menu) == 1)
                        $arr_Row[] = $menu;

                    $arr_Row[] =  [['callback_data' => $nextData,'text'=> "$nextText"]];
                    $arr_Row[] =  [['callback_data' => "back",'text'=> "$backText"],['callback_data' => "home",'text'=> $homeText]];

                } else {
                    $bl = FALSE;
                    return $bl;
                }
                return $menu = json_encode(['inline_keyboard' => $arr_Row]);
            } else {
                $sql = "SELECT ".$this->whichSelect." FROM " . $this->table_name; 
            }
        }

        public function ClickBtnInlinePrev($whichSelect,$wherePart) {
            $this->whichSelect = $whichSelect;
            $this->wherePart = $wherePart;
            if (!empty($this->wherePart)) {
                $sql = "SELECT * FROM ( SELECT $whichSelect FROM $this->table_name WHERE $this->wherePart ) AS t ORDER BY t.id ASC"; 
                
                // return $sql;
                $result = $this->conn->query($sql);
                if ($result->num_rows > 0) {
                    $menu = [];
                    $b = 1;
                    $arr_Row= [];
                    while ($row = $result->fetch_assoc()) {
                        if ($b == 1) {
                            $prevData = $row["id"]."$this->prevWord";
                        }
                        $id = $row["id"];
                        $updateClDt = $id."_".$this->updateKeyboard;
                        $deleteClDt = $id."_".$this->deleteKeyboard;
                        $texts_category = "Dissconnect";
                        if ($this->step_1 == 1) {
                            $texts_category = base64_decode($row["title_uz"]);
                            $nextText = "Keyingi ➡️";
                            $prevText = "⬅️ Oldingi";
                            $homeText = "🏘 Bosh Menu";    
                            $backText = "🔙 Ortga";
                            $anotherWorkType = "Boshq ish turi";
                        } else if($this->step_1 == 2){
                            $texts_category = base64_decode($row["title_ru"]);
                            $nextText = "следующий ➡️";
                            $prevText = "⬅️ Предыдущий ";
                            $backText = "🔙 Назад";
                            $homeText = "🏘 Главное меню";
                            $anotherWorkType = "Другой вид работы";
                        } else if($this->step_1 == 3){
                            $texts_category = base64_decode($row["title_uz"]);
                            $nextText = "Кейинги ➡️";
                            $prevText = "⬅️ Олдинги";
                            $homeText = "🏘 Бош Мену";    
                            $backText = "🔙 Ортга";
                            $anotherWorkType = "Бошқ иш тури";
                        }

                        if (empty($this->updateKeyboard) or empty($this->deleteKeyboard)){
                            $callback_id = $row['id']."$this->callback_data";
                            $menu[] =  ['callback_data' => "$callback_id",'text'=> "$texts_category"];

                            
                            $arr_Row[] = $menu;
                            $menu = [];
                            
                        } else if (!empty($this->updateKeyboard) and !empty($this->deleteKeyboard)) {
                            $callback_id = $row['id']."$this->callback_data";
                            $menu[] =  ['callback_data' => "$callback_id",'text'=> "$texts_category"];
                            $arr_Row[] = $menu;
                            $menu = [];
                            $update[] = ["callback_data" => $updateClDt,"text" => "🔄 O`zgartirish"];
                            $update[] = ["callback_data" => $deleteClDt,"text" => "❌ O`chirish"];
                            $arr_Row[] = $update;
                            $update = [];
                        }
                    $b++;        
                    }
                    $nextData = $id."$this->nextWord";
                   
                    if(count($menu) == 1)
                        $arr_Row[] = $menu;

                    if (empty($this->stopAnotherWork)) {
                        $arr_Row[] =  [['callback_data' => "anotherWork",'text'=> "$anotherWorkType"]];
                    } else if(!empty($this->stopAnotherWork)) {
                        $arr_Row[] =  [['callback_data' => $this->dataExt,'text'=> $this->$txtExt]];
                    }

                    $arr_Row[] =  [['callback_data' => $prevData,'text'=> "$prevText"],['callback_data' => $nextData,'text'=> "$nextText"],];
                    $arr_Row[] =  [['callback_data' => "back",'text'=> "$backText"],['callback_data' => "home",'text'=> $homeText],];

                } else {
                    if (!empty($this->defoultWhere)) {
                        $sql = "SELECT * FROM ( SELECT $whichSelect FROM $this->table_name WHERE $this->defoultWhere ) AS t ORDER BY t.id ASC"; 
                    } else {
                        $sql = "SELECT * FROM $this->table_name WHERE status = 1 ORDER BY id DESC limit 10  "; 
                    }
                    $result = $this->conn->query($sql);
                    if ($result->num_rows > 0) {
                        $menu = [];
                        $b = 1;
                        $arr_Row= [];
                        while ($row = $result->fetch_assoc()) {
                            if ($b == 1) {
                                $prevData = $row["id"]."$this->prevWord";
                            }
                            $id = $row["id"];
                            $updateClDt = $id."_".$this->updateKeyboard;
                            $deleteClDt = $id."_".$this->deleteKeyboard;
                            $texts_category = "Dissconnect";
                            if ($this->step_1 == 1) {
                                $texts_category = base64_decode($row["title_uz"]);
                                $nextText = "Keyingi ➡️";
                                $prevText = "⬅️ Oldingi";
                                $homeText = "🏘 Bosh Menu";    
                                $backText = "🔙 Ortga";
                                $anotherWorkType = "Boshq ish turi";
                            } else if($this->step_1 == 2){
                                $texts_category = base64_decode($row["title_ru"]);
                                $nextText = "следующий ➡️";
                                $prevText = "⬅️ Предыдущий ";
                                $backText = "🔙 Назад";
                                $homeText = "🏘 Главное меню";
                                $anotherWorkType = "Другой вид работы";
                            } else if($this->step_1 == 3){
                                $texts_category = base64_decode($row["title_uz"]);
                                $nextText = "Кейинги ➡️";
                                $prevText = "⬅️ Олдинги";
                                $homeText = "🏘 Бош Мену";    
                                $backText = "🔙 Ортга";
                                $anotherWorkType = "Бошқ иш тури";
                            }

                            if (empty($this->updateKeyboard) or empty($this->deleteKeyboard)){
                                    $callback_id = $row['id']."$this->callback_data";
                                    $menu[] =  ['callback_data' => "$callback_id",'text'=> "$texts_category"];

                                    $arr_Row[] = $menu;
                                    $menu = [];
                                    
                                } else if (!empty($this->updateKeyboard) and !empty($this->deleteKeyboard)) {
                                    $callback_id = $row['id']."$this->callback_data";
                                    $menu[] =  ['callback_data' => "$callback_id",'text'=> "$texts_category"];
                                    $arr_Row[] = $menu;
                                    $menu = [];
                                    $update[] = ["callback_data" => $updateClDt,"text" => "🔄 O`zgartirish"];
                                    $update[] = ["callback_data" => $deleteClDt,"text" => "❌ O`chirish"];
                                    $arr_Row[] = $update;
                                    $update = [];
                                }
                        $b++;        
                        }
                        $nextData = $id."$this->nextWord";
                       
                        if(count($menu) == 1)
                            $arr_Row[] = $menu;

                        if (empty($this->stopAnotherWork)) {
                            $arr_Row[] =  [['callback_data' => "anotherWork",'text'=> "$anotherWorkType"]];
                        } else if(!empty($this->stopAnotherWork)) {
                            $arr_Row[] =  [['callback_data' => $this->dataExt,'text'=> $this->$txtExt]];
                        }

                        $arr_Row[] =  [['callback_data' => $prevData,'text'=> "$prevText"],['callback_data' => $nextData,'text'=> "$nextText"],];
                        $arr_Row[] =  [['callback_data' => "back",'text'=> "$backText"],['callback_data' => "home",'text'=> $homeText],];
                    }
                }
                return $menu = json_encode(['inline_keyboard' => $arr_Row]);
            } else {
                $sql = "SELECT ".$this->whichSelect." FROM " . $this->table_name; 
            }
        }

        public function ClickBtnInlinePrevUser($whichSelect,$wherePart) {
            $this->whichSelect = $whichSelect;
            $this->wherePart = $wherePart;
            if (!empty($this->wherePart)) {
                $sql = "SELECT * FROM ( SELECT $whichSelect FROM $this->table_name WHERE $this->wherePart ) AS t ORDER BY t.id ASC"; 
                
                // return $sql;
                $result = $this->conn->query($sql);
                if ($result->num_rows > 0) {
                    $menu = [];
                    $b = 1;
                    $arr_Row= [];
                    while ($row = $result->fetch_assoc()) {
                        if ($b == 1) {
                            $prevData = $row["id"]."$this->prevWord";
                        }
                        $id = $row["id"];
                        $updateClDt = $id."_".$this->updateKeyboard;
                        $deleteClDt = $id."_".$this->deleteKeyboard;
                        $texts_category = base64_decode($row["full_name"]);
                        $nextText = "Keyingi ➡️";
                        $prevText = "⬅️ Oldingi";
                        $homeText = "🏘 Bosh Menu";    
                        $backText = "🔙 Ortga";

                        if (empty($this->updateKeyboard) or empty($this->deleteKeyboard)){
                            $callback_id = $row['id']."$this->callback_data";
                            $menu[] =  ['callback_data' => "$callback_id",'text'=> "$texts_category"];

                                $arr_Row[] = $menu;
                                $menu = [];
                            
                        } else if (!empty($this->updateKeyboard) and !empty($this->deleteKeyboard)) {
                            $callback_id = $row['id']."$this->callback_data";
                            $menu[] =  ['callback_data' => "$callback_id",'text'=> "$texts_category"];
                            $arr_Row[] = $menu;
                            $menu = [];
                            // $update[] = ["callback_data" => $updateClDt,"text" => "🔄 O`zgartirish"];
                            $update[] = ["callback_data" => $deleteClDt,"text" => "❌ O`chirish"];
                            $arr_Row[] = $update;
                            $update = [];
                        }
                    $b++;        
                    }
                    $nextData = $id."$this->nextWord";
                   
                    if(count($menu) == 1)
                        $arr_Row[] = $menu;

                    $arr_Row[] =  [['callback_data' => $prevData,'text'=> "$prevText"],['callback_data' => $nextData,'text'=> "$nextText"],];
                    $arr_Row[] =  [['callback_data' => "back",'text'=> "$backText"],['callback_data' => "home",'text'=> $homeText],];

                } else {
                    if (!empty($this->defoultWhere)) {
                        $sql = "SELECT * FROM ( SELECT $whichSelect FROM $this->table_name WHERE $this->defoultWhere ) AS t ORDER BY t.id ASC"; 
                    } else {
                        $sql = "SELECT * FROM $this->table_name WHERE status = 1 ORDER BY id DESC limit 10  "; 
                    }
               
                    $result = $this->conn->query($sql);
                    if ($result->num_rows > 0) {
                        $menu = [];
                        $b = 1;
                        $arr_Row= [];
                        while ($row = $result->fetch_assoc()) {
                            if ($b == 1) {
                                $prevData = $row["id"]."$this->prevWord";
                            }
                            $id = $row["id"];
                            $updateClDt = $id."_".$this->updateKeyboard;
                            $deleteClDt = $id."_".$this->deleteKeyboard;
                            $texts_category = base64_decode($row["full_name"]);
                            $nextText = "Keyingi ➡️";
                            $prevText = "⬅️ Oldingi";
                            $homeText = "🏘 Bosh Menu";    
                            $backText = "🔙 Ortga";

                            if (empty($this->updateKeyboard) or empty($this->deleteKeyboard)){
                                    $callback_id = $row['id']."$this->callback_data";
                                    $menu[] =  ['callback_data' => "$callback_id",'text'=> "$texts_category"];

                                    $arr_Row[] = $menu;
                                    $menu = [];
                                    
                                } else if (!empty($this->updateKeyboard) and !empty($this->deleteKeyboard)) {
                                    $callback_id = $row['id']."$this->callback_data";
                                    $menu[] =  ['callback_data' => "$callback_id",'text'=> "$texts_category"];
                                    $arr_Row[] = $menu;
                                    $menu = [];
                                    // $update[] = ["callback_data" => $updateClDt,"text" => "🔄 O`zgartirish"];
                                    $update[] = ["callback_data" => $deleteClDt,"text" => "❌ O`chirish"];
                                    $arr_Row[] = $update;
                                    $update = [];
                                }
                        $b++;        
                        }
                        $nextData = $id."$this->nextWord";
                       
                        if(count($menu) == 1)
                            $arr_Row[] = $menu;

                        $arr_Row[] =  [['callback_data' => $prevData,'text'=> "$prevText"],['callback_data' => $nextData,'text'=> "$nextText"],];
                        $arr_Row[] =  [['callback_data' => "back",'text'=> "$backText"],['callback_data' => "home",'text'=> $homeText],];
                    }
                }
                return $menu = json_encode(['inline_keyboard' => $arr_Row]);
            } else {
                $sql = "SELECT ".$this->whichSelect." FROM " . $this->table_name; 
            }
        }

        public function insert($ColumnsPart,$valuesPart) {
            $this->ColumnsPart = $ColumnsPart;
            $this->valuesPart = $valuesPart;
            $sql = "INSERT INTO ". $this->table_name . " (".$this->ColumnsPart.") VALUES (".$this->valuesPart.")";
            // return $sql; 
            return  $result = $this->conn->query($sql);
        }

        public function insertId($ColumnsPart,$valuesPart) {
            $this->ColumnsPart = $ColumnsPart;
            $this->valuesPart = $valuesPart;
            $sql = "INSERT INTO ". $this->table_name . " (".$this->ColumnsPart.") VALUES (".$this->valuesPart.")";
            $this->conn->query($sql);
            $last_insert_id = mysqli_insert_id($this->conn);
            return  $last_insert_id;
        }

        public function delete($wherePart) {
            if (!empty($wherePart)) {
                $this->wherePart = $wherePart;
                $sql = "DELETE FROM ".$this->table_name . " WHERE " . $this->wherePart;
                // return $sql;
                $this->conn->query($sql);
            }
        }
       
        public function giveKeyboardsUrl ($whichSelect,$wherePart) {
            $this->whichSelect = $whichSelect;
            $this->wherePart = $wherePart;
            if (!empty($this->wherePart)) {
                $sql = "SELECT ".$this->whichSelect." FROM " . $this->table_name ." WHERE " . $this->wherePart;
                $result = $this->conn->query($sql);
                if ($result->num_rows > 0) {
                    $menu = [];
                    $arr_Row= [];
                    while ($row = $result->fetch_assoc()) {
                        $id = $row["id"];
                        $btnName = base64_decode($row["btn_name"]);
                        $btnUrl = base64_decode($row["btn_url"]);
                        $menu[] =  ['url' => "$btnUrl",'text'=> $btnName];
                        $arr_Row[] = $menu;
                        $menu = [];
                            
                    }
                    return $arr_Row;
                    // return $menu = json_encode(['inline_keyboard' => $arr_Row]);
                   
                } 

            }
        }

    }


    $sql = new ActionWithSql();
    $sql->SetTable("admins");
    $result = $sql->select("*","");
    if ($result->num_rows > 0) {
        $admin = [];
        while ($row = $result->fetch_assoc()) {
            $admin[] = $row["chat_id"];
        }
    } 

?>