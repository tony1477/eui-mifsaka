<?php
class ChatController extends Controller {
	public $menuname = 'chat';
  
  private function processMessage($message) {
    $reply = 'Maaf ya, perintah tidak dikenal. Nanti saya tanyakan lagi ke Administrator';
    $message_id = $message['message_id'];
    $chat_id = $message['chat']['id'];
    if (isset($message['text'])) {
      // incoming text message
      $from = $message['from'];
      $usertelegram = $from['username'];
      $text = $message['text'];
      $sql = "select ifnull(count(1),0)
        from chatgroup a 
        left join chat b on b.chatid = a.chatid 
        where b.msgfrom = '".$text."'";
      $isok = Yii::app()->db->createCommand($sql)->queryScalar();
      //jika chat bukan basis otorisasi, langsung reply
      if ($isok == 0) {
        $sql = "select msgreply,sourcedata from chat where msgfrom = '".$text."'";
        $datachat = Yii::app()->db->createCommand($sql)->queryRow();
        $reply = $datachat['msgreply'];
      } else 
      //jika chat basis otorisasi, maka cek apakah user berhak / tidak
      if ($isok > 0) {
        $sql = "select ifnull(count(1),0)
        from chatgroup a
        left join usergroup b on b.groupaccessid = a.groupaccessid 
        left join useraccess c on c.useraccessid = b.useraccessid 
        left join chat d on d.chatid = a.chatid 
        where c.telegramid = '".$usertelegram."' and d.msgfrom = '".$text."'";
        $isok = Yii::app()->db->createCommand($sql)->queryScalar();

        if ($isok == 0) {
          $reply = 'Maaf, saya tidak mengenal Anda. Tolong diinfokan ke administrator untuk ditambahkan, terima kasih ';
        } else {
          $sql = "select ifnull(count(1),0) 
            from chat a  
            where msgfrom = '".$text."'";
          $datachat = Yii::app()->db->createCommand($sql)->queryScalar();
          if ($datachat > 0) {
            $sql = "select msgreply,sourcedata from chat where msgfrom = '".$text."'";
            $datachat = Yii::app()->db->createCommand($sql)->queryRow();
            $reply = $datachat['msgreply'];
            if (($datachat['sourcedata'] != null) && ($datachat['sourcedata'] != '')) {
              $sql = str_replace('[usertelegram]',$usertelegram,$datachat['sourcedata']);
              $dataku = Yii::app()->db->createCommand($sql)->queryAll();
              $s = '';
              foreach ($dataku as $data) {
                $keys = array_keys($data);
                foreach ($keys as $key) {
                  if (strpos($datachat['msgreply'],'[list]') === FALSE) {
                    $datachat['msgreply'] = str_replace($key,$data[$key], $datachat['msgreply']);
                  } else {
                    $s .= $key.' : '.$data[$key]. "\n";
                  }   
                }
                if (strpos($datachat['msgreply'],'[list]') === FALSE) {
                  $reply = $datachat['msgreply'];  
                } else {
                  $reply .= $s;
                }
              }
              $reply = str_replace(']','',str_replace('[','',$reply));
            }
          }
        } 
        //WriteTelegramLog($reply);
      }
      apiRequest("sendMessage", array('chat_id' => $chat_id, "text" => $reply,'parse_mode'=>'html'));
    } else {
      //WriteTelegramLog("Perintah tidak dikenal");
      apiRequest("sendMessage", array('chat_id' => $chat_id, "text" => 'Maafkan kebodohan saya, saya hanya mengenal perintah tulisan'));
    }
  }
  public function actionReplyBot(){
    $content = file_get_contents("php://input");
    WriteTelegramLog($content);
    $update = json_decode($content, TRUE);
    if (!$update) {
      exit;
    }
    if (isset($update['message'])) {
      $this->processMessage($update['message']);
    }
  }
}