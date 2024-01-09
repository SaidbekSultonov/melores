<?php
if (isset($users_section) && !empty($users_section)) {
    foreach ($users_section as $users_section_value) {
        $id = 0;
        $title = '';
        if (isset($users_section_value['second_name']) && !empty($users_section_value['second_name'])) {
            $title = $users_section_value['second_name'];
        }
        if (isset($users_section_value['id']) && !empty($users_section_value['id'])) {
            $id = $users_section_value['id'];
        }
        ?>
        <option value="<?=$id?>"><?=$title?></option>
        <?php
    }
}
?>