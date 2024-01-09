<?php 

// ===================================================================== NATFICATION CLASS

    class Natfication
    {
        public $chat_id;
        public $text;
        public $keyboard;
        public $audio;        
        public $photo;        
        public $video;
        public $voice;
        public $video_note;
        public $document;
        public $message_id;
        public $caption;

        public function sendMessage() {
            if (!empty($this->keyboard)) {
                $getResult = bot('sendMessage',[
                    'chat_id' => $this->chat_id,
                    'text' => $this->text,
                    'reply_markup' => $this->keyboard,
                    'parse_mode' => "html"
                ]);
                return $getResult;
            } else {

                $getResult = bot('sendMessage',[
                    'chat_id' => $this->chat_id,
                    'text' => $this->text,
                    'parse_mode' => "html"
                ]);
                return $getResult;
            }
        }

        public  function sendMessageInlineKeyboard() {
            bot('sendMessage',[
                'chat_id' => $this->chat_id,
                'text' => $this->text,
                'reply_markup' => $this->keyboard,
                'parse_mode' => "html"
            ]);
        } 
        
        public function sendMessageKeyboard() {
            bot('sendMessage',[
                'chat_id' => $this->chat_id,
                'text' => $this->text,
                'reply_markup' => $this->keyboard,
                'parse_mode' => "html"
            ]);
        }

        public function editKeyboard() {
            bot('editMessageReplyMarkup',[
                'chat_id' => $this->chat_id,
                'message_id' => $this->message_id,
                'reply_markup' => $this->keyboard
            ]);
        }

        public function editMessageText() {
            bot('editMessageText',[
                'chat_id' => $this->chat_id,
                'text' => $this->text,
                'message_id' => $this->message_id,
                'reply_markup' => $this->keyboard
            ]);
        }

        public function deleteMessage() {
            bot('deleteMessage',[
                'chat_id' => $this->chat_id,
                'message_id' => $this->message_id
            ]);
        }

        public function sendAudio() {
            if (!empty($this->keyboard)) {
                bot('sendAudio',[
                    'chat_id' => $this->chat_id,
                    'audio' => $this->audio,
                    'caption' => $this->caption,
                    'reply_markup' => $this->keyboard
                ]);
            } else {
                bot('sendAudio',[
                    'chat_id' => $this->chat_id,
                    'audio' => $this->audio,
                    'caption' => $this->caption
                ]);
            }
        }   

        public function sendPhoto() {
            bot("sendPhoto",[
                'chat_id' => $this->chat_id,
                'photo' => $this->photo,
                'caption' => $this->caption,
                'reply_markup' => $this->keyboard
            ]);
        }

        public function forwardMessage () {
            bot("forwardMessage",[
                'chat_id' => $this->chat_id,
                'from_chat_id' => ADMIN,
                'message_id' => $this->message_id
            ]);
        }

        public function sendVideo(){
            if (empty($this->keyboard)) {
                bot('sendVideo',[
                    'chat_id' => $this->chat_id,
                    'video' => $this->video,
                    'caption' => $this->caption,
                    'reply_markup' => $this->keyboard
                ]);
            } else {
                 bot('sendVideo',[
                    'chat_id' => $this->chat_id,
                    'video' => $this->video,
                    'caption' => $this->caption
                ]);
            }

        }

        public function sendDocument() {
            if (!empty($this->keyboard)) {            
                bot("sendDocument",[
                    'chat_id' => $this->chat_id,
                    'document' => $this->document,
                    'caption' => $this->caption,
                    'reply_markup' => $this->keyboard
                ]);
            } else {
                bot("sendDocument",[
                    'chat_id' => $this->chat_id,
                    'document' => $this->document,
                    'caption' => $this->caption
                ]);
            }

        }

        public function sendVideoNote() {
            if (!empty($this->keyboard)) {
                bot("sendVideoNote",[
                    'chat_id' => $this->chat_id,
                    'video_note' => $this->video_note,
                    'reply_markup' => $this->keyboard
                ]);
            } else {
                bot("sendVideoNote",[
                    'chat_id' => $this->chat_id,
                    'video_note' => $this->video_note
                ]);
            }

        }

        public function sendVoice() {
            if (!empty($this->keyboard)) {
                bot('sendVoice',[
                    'chat_id' => $this->chat_id,
                    'voice' => $this->voice,
                    'caption' => $this->caption,
                    'reply_markup' => $this->keyboard
                ]);
            } else {
                bot('sendVoice',[
                    'chat_id' => $this->chat_id,
                    'voice' => $this->voice,
                    'caption' => $this->caption
                ]);
            }

        }

    }




?>