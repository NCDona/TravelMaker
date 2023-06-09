<?php
try{
  require_once("./connectdatabase.php");
  if ($_FILES['file']['error'] === UPLOAD_ERR_OK){
      if (($_FILES['file']['size'] / 1024) > 1024){
        echo 'tooBig';
      }else if(checkIsImage($_FILES['file']['tmp_name']) !== false){
        $info= getimagesize($_FILES['file']['tmp_name']);        
        $ext = image_type_to_extension($info['2']);      

        $file = $_FILES['file']['tmp_name'];
        $dest = '../images/memIcon/' . 'memIcon-0' . $_REQUEST['memNo'] . $ext;
        //新增資料夾
        // $file_path = '../images/test/'.'memNo' . $_REQUEST['memNo'].'/';//資料夾路徑
        // mkdir($file_path);
        move_uploaded_file($file, $dest);

        $memName = $_REQUEST['memName'];
        $memPsw = $_REQUEST['memPsw'];
        $memNo = $_REQUEST['memNo'];
        $memIcon = 'memIcon-0' . $_REQUEST['memNo'] . $ext;

        $sql = "UPDATE `member` SET `memName`='$memName',`memPsw`='$memPsw',`memIcon`='$memIcon' WHERE `memNo`='$memNo';"; 


        $member = $pdo->exec($sql);

        $result = [
          'memName'=>$memName,
          'memIcon'=>$memIcon,
        ];
        echo json_encode($result);
      }else{
        echo 'notImg';
      };
  }else{
    echo '錯誤代碼：' . $_FILES['file']['error'] . '<br/>';
  }
    
}catch(PDOException $e){
  echo $e->getMessage();
}

function checkIsImage($filename){   
  $alltypes = '.jpeg|.png';//定义检查的图片类型    
  if(file_exists($filename)){        
    $info= getimagesize($filename);        
    $ext = image_type_to_extension($info['2']);        
      return stripos($alltypes,$ext);    
  }else{        
      return false;   
  }
} 

?>