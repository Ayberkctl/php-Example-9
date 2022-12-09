<?php


function multi_upload($dosyalar){
    $sonuc=[];

    //hataları kontrol
    foreach ($dosyalar['error'] as $index => $error){
        if($error==4){
            $sonuc["hata"]=" lütfen dosya seçin";
        }
        elseif($error !=0){
            $sonuc["hata"][]="dosya yüklenirekn hata oluştu  #".$dosyalar["name"][$index];

        }
    }
    //hata yoksa
    if(!isset($sonuc["hata"])){
        
        global $gecerli_dosya_uzantilari;
        foreach($dosyalar["type"] as $index => $type){
            if(!in_array($type,$gecerli_dosya_uzantilari)){
                $sonuc["hata"][]="dosya geçersiz  formata #".$dosyalar["name"][$index];
            }
        }
        if(!isset($sonuc["hata"])){
            //size control
            $maxsize=(1024 * 1024);
            foreach($dosyalar["size"] as $index => $size){
                if($size > $maxsize){
                    $sonuc["hata"][]="dosya boyutu fazla #".$dosyalar["name"][$index];
                }
            }
            if(!isset($sonuc["hata"])){
                foreach($dosyalar["tmp_name"]as  $index => $tmp){
                    $dosyaAdi=$dosyalar["name"][$index];
                    $yukle = move_uploaded_file($tmp, 'upload/' .$dosyaAdi); 
                    if($yukle){
                        $sonuc["dosya"][]='upload/' .$dosyaAdi;
                    }else{
                        $sonuc["hata"][]="dosya yüklenemedi #".$dosyaAdi;
                    }
                }
            }
        }

    }
    return $sonuc;
}
    
$gecerli_dosya_uzantilari = [
    'image/jpeg',
    'image/png',
    'image/gif'
];
$sonuc = multi_upload($_FILES["dosya"]);

if(isset($sonuc["dosya"])){
    print_r($sonuc["dosya"]);

    if(isset($sonuc["hata"])){
        print_r($sonuc["hata"]);
    }
}elseif(isset($sonuc["hata"])){
    if(is_array($sonuc["hata"])){
        echo implode( "<br>", $sonuc["hata"] );
    }else{
        echo $sonuc["hata"];
    }
}


?>