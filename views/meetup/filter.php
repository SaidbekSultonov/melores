<?php

function bot($method, $data = []) {
    $url = 'https://api.telegram.org/bot1656261809:AAFUxaShUltI6zG6KdDrdUQvLQYyp1x7BAU/'.$method;
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    $res = curl_exec($ch);
    
    if(curl_error($ch)){
        var_dump(curl_error($ch));
    } else {
        return json_decode($res);
    }
}

if (isset($filtered) && !empty($filtered)) {
    $number = 0;
    foreach ($filtered as $da_value) {
        $number += 1;
    ?>
        <tr>
            <td><?=$number ?></td>
            <td><?php
                if (isset($da_value['second_name']) && !empty($da_value['second_name'])) {
                    echo $da_value['second_name'];
                }else {
                    echo 'Foydalanuvchi ismi yoq!';
                }
            ?></td>
            <td><?php
                if (isset($da_value['date']) && !empty($da_value['date'])) {
                    echo $da_value['date'];
                }else {
                    echo 'Sana xato ketgan!';
                }
            ?></td>
            <td><?php
                if (isset($da_value['type']) && !empty($da_value['type'])) {
                    switch ($da_value['type']) {
                        case '1':
                            echo 'Admin';
                        break;
                        case '2':
                            echo 'Nazoratchi';
                        break;
                        case '3':
                            echo 'O`TK';
                        break;
                        case '4':
                            echo 'Sotuvchi';
                        break;
                        case '5':
                            echo 'Bo`lim boshlig`i';
                        break;
                        case '6':
                            echo 'Kroychi';
                        break;
                        case '7':
                            echo 'Bo`lim ishchisi';
                        break;
                    }
                }else {
                    echo 'Foydalanuvchi ro`li yoq!';
                }
            ?></td>
            <td><?php
                if (isset($da_value['da_type']) && $da_value['da_type'] == 1) {
                    if (isset($da_value['file_id']) && preg_match('~^\d+$~',$da_value['file_id'])) {
                        echo 'Jarima: '.$da_value['file_id'];
                    }else {
                        echo 'Jarima: 0';
                    }
                }elseif (isset($da_value['file_id']) && !empty($da_value['file_id'])) {
                    $pdfFile = bot('getFile', [
                        'file_id' => $da_value['file_id']
                    ]);
                    if(isset($pdfFile) && !empty($pdfFile)) {
                        $file = 'https://api.telegram.org/file/bot1656261809:AAFUxaShUltI6zG6KdDrdUQvLQYyp1x7BAU/'.$pdfFile->result->file_path;
                        echo '<a download href=\''.$file.'\'>PDF</a>                        
                        <a data-target="#modal-default" data-toggle="modal" class="view-pdf" data-url="'.$pdfFile->result->file_path.'" title="View" aria-label="View"><span class="glyphicon glyphicon-eye-open"></span></a>';
                    }else {
                        echo 'Document topilmadi!!';
                    }
                }else {
                    echo 'Document topilmadi!!';
                }
            ?></td>
        </tr>
    <?php
    }
}else {
    ?>
        <tr>
            <td colspan="5">
                <center class="empty">Hech qanday natija topilmadi.</center>
            </td>
        </tr>
    <?php
}
?>