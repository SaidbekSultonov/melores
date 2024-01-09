<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Trade Service Statistics';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="trade-service-statistics-index">
    <ul class="nav nav-tabs" id="custom-content-above-tab" role="tablist">
        <li class="nav-item active">
            <a class="nav-link" id="custom-content-above-home-tab" data-toggle="pill" href="#custom-content-above-home" role="tab" aria-controls="custom-content-above-home" aria-selected="true">Bo'limlar bo'yicha</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" id="custom-content-above-answer-tab" data-toggle="pill" href="#custom-content-above-answer" role="tab" aria-controls="custom-content-above-answer" aria-selected="false">Foydalanuvchilar bo'yicha</a>
        </li>
    </ul>
    <div class="tab-content" id="custom-content-above-tabContent">
        <div class="tab-pane active" id="custom-content-above-home" role="tabpanel" aria-labelledby="custom-content-above-home-tab">
            <div class="box box-primary box-solid collapsed-box" style="margin-top: 20px !important">
                <div class="box-header with-border">
                    <h3 class="box-title">Yo'nalishlar</h3>
                    <div class="box-tools pull-right">
                        <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-plus"></i>
                        </button>
                    </div>
                </div>
                <div class="box-body" style="display: none;">
                    <table class='table table-bordered table-striped mt-20'>
                        <thead>
                        <tr>
                            <th>#</th>
                            <th>Nomi UZ</th>
                            <th>Nomi RU</th>
                            <th>Ko'rilgan soni</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php
                        if (isset($service) and !empty($service)){
                            $i = 1;
                            foreach ($service as $key => $value) {
                                echo "<tr>";
                                echo "<td>".$i."</td>";
                                echo "<td>".$value->title_uz."</td>";
                                echo "<td>".$value->title_ru."</td>";
                                echo "<td>".$value->click_count."</td>";
                                echo "</tr>";
                                $i++;
                            }
                        } else {
                            echo "<tr>";
                            echo  "<td class='text-center' colspan=4'>Ma'lumot yo'q</td>";
                            echo "</tr>";
                        }
                        ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="box box-primary box-solid collapsed-box" style="margin-top: 5px !important">
                <div class="box-header with-border">
                    <h3 class="box-title">Bo'limlar</h3>
                    <div class="box-tools pull-right">
                        <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-plus"></i>
                        </button>
                    </div>
                </div>
                <div class="box-body" style="display: none;">
                    <table class='table table-bordered table-striped mt-20'>
                        <thead>
                        <tr>
                            <th>#</th>
                            <th>Nomi UZ</th>
                            <th>Nomi RU</th>
                            <th>Ko'rilganlar soni</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php
                        if (isset($serviceType) and !empty($serviceType)){
                            $i = 1;
                            foreach ($serviceType as $key => $value) {
                                echo "<tr>";
                                echo "<td>".$i."</td>";
                                echo "<td>".$value->title_uz."</td>";
                                echo "<td>".$value->title_ru."</td>";
                                echo "<td>".$value->click_count."</td>";
                                echo "</tr>";
                                $i++;
                            }
                        } else {
                            echo "<tr>";
                            echo  "<td class='text-center' colspan=4'>Ma'lumot yo'q</td>";
                            echo "</tr>";
                        }
                        ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="box box-primary box-solid collapsed-box" style="margin-top: 5px !important">
                <div class="box-header with-border">
                    <h3 class="box-title">Kompaniyalar</h3>
                    <div class="box-tools pull-right">
                        <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-plus"></i>
                        </button>
                    </div>
                </div>
                <div class="box-body" style="display: none;">
                    <table class='table table-bordered table-striped mt-20'>
                        <thead>
                        <tr>
                            <th>#</th>
                            <th>Nomi UZ</th>
                            <th>Nomi RU</th>
                            <th>Ko'rilganlar soni</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php
                        if (isset($company) and !empty($company)){
                            $i = 1;
                            foreach ($company as $key => $value) {
                                echo "<tr>";
                                echo "<td>".$i."</td>";
                                echo "<td>".$value->title_uz."</td>";
                                echo "<td>".$value->title_ru."</td>";
                                echo "<td>".$value->click_count."</td>";
                                echo "</tr>";
                                $i++;
                            }
                        } else {
                            echo "<tr>";
                            echo  "<td class='text-center' colspan=4'>Ma'lumot yo'q</td>";
                            echo "</tr>";
                        }
                        ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="box box-primary box-solid collapsed-box" style="margin-top: 5px !important">
                <div class="box-header with-border">
                    <h3 class="box-title">Hududlar</h3>
                    <div class="box-tools pull-right">
                        <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-plus"></i>
                        </button>
                    </div>
                </div>
                <div class="box-body" style="display: none;">
                    <table class='table table-bordered table-striped mt-20'>
                        <thead>
                        <tr>
                            <th>#</th>
                            <th>Nomi UZ</th>
                            <th>Nomi RU</th>
                            <th>Ko'rilganlar soni</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php
                        if (isset($district) and !empty($district)){
                            $i = 1;
                            foreach ($district as $key => $value) {
                                echo "<tr>";
                                echo "<td>".$i."</td>";
                                echo "<td>".$value->title_uz."</td>";
                                echo "<td>".$value->title_ru."</td>";
                                echo "<td>".$value->click_count."</td>";
                                echo "</tr>";
                                $i++;
                            }
                        } else {
                            echo "<tr>";
                            echo  "<td class='text-center' colspan=4'>Ma'lumot yo'q</td>";
                            echo "</tr>";
                        }
                        ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="tab-pane fade" id="custom-content-above-answer" role="tabpanel" aria-labelledby="custom-content-above-answer-tab">
            <table class='table table-bordered table-striped mt-20'>
                <thead>
                <tr>
                    <th>#</th>
                    <th>Telefon raqam</th>
                    <th>Nomi UZ</th>
                    <th>Nomi RU</th>
                    <th>Ko'rilganlar soni</th>
                </tr>
                </thead>
                <tbody>
                <?php
                if (isset($model) and !empty($model)){
                    $i = 1;
                    foreach ($model as $key => $value) {
                        echo "<tr>";
                        echo "<td>".$i."</td>";
                        echo "<td>";
                        if (isset($value->phone->phone_number) and !empty($value->phone->phone_number)){
                            echo $value->phone->phone_number;
                        } else {
                            echo 'Telefon raqam mavjud emas!';
                        }
                        echo "</td>";
                        if ($value->type == 'service'){
                            echo "<td>";
                            if (isset($value->service->title_uz) and !empty($value->service->title_uz)){
                                echo $value->service->title_uz;
                            } else {
                                echo '';
                            }
                            echo "</td>";
                            echo "<td>";
                            if (isset($value->service->title_ru) and !empty($value->service->title_ru)){
                                echo $value->service->title_ru;
                            } else {
                                echo '';
                            }
                            echo "</td>";
                        }
                        else if ($value->type == 'service_type'){
                            echo "<td>";
                            if (isset($value->sety->title_uz) and !empty($value->sety->title_uz)){
                                echo $value->sety->title_uz;
                            } else {
                                echo '';
                            }
                            echo "</td>";
                            echo "<td>";
                            if (isset($value->sety->title_ru) and !empty($value->sety->title_ru)){
                                echo $value->sety->title_ru;
                            } else {
                                echo '';
                            }
                            echo "</td>";
                        }
                        else if ($value->type == 'company'){
                            echo "<td>";
                            if (isset($value->company->title_uz) and !empty($value->company->title_uz)){
                                echo $value->company->title_uz;
                            } else {
                                echo '';
                            }
                            echo "</td>";
                            echo "<td>";
                            if (isset($value->company->title_ru) and !empty($value->company->title_ru)){
                                echo $value->company->title_ru;
                            } else {
                                echo '';
                            }
                            echo "</td>";
                        }
                        else if ($value->type == 'district'){
                            echo "<td>";
                            if (isset($value->district->title_uz) and !empty($value->district->title_uz)){
                                echo $value->district->title_uz;
                            } else {
                                echo '';
                            }
                            echo "</td>";
                            echo "<td>";
                            if (isset($value->district->title_ru) and !empty($value->district->title_ru)){
                                echo $value->district->title_ru;
                            } else {
                                echo '';
                            }
                            echo "</td>";
                        }
                        echo "<td>".$value->click_count."</td>";
                        echo "</tr>";
                        $i++;
                    }
                } else {
                    echo "<tr>";
                    echo  "<td class='text-center' colspan=5'>Ma'lumot yo'q</td>";
                    echo "</tr>";
                }
                ?>
                </tbody>
            </table>
        </div>
    </div>
</div>