<?php

    $user = $data;
    $dates = $data['dates'];
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <style>
        @page {
            size: legal landscape;
        }
        .limiter {
            width: 100%;
            margin: 0 auto;
        }

        /* Estilo para la tabla */
        table {
            width: 100%;
            /*border-collapse: collapse;*/
            /*margin: 20px 0;*/

            border-spacing: 1px;
            border-collapse: collapse;
            background: white;
            border-radius: 10px;
            overflow: hidden;
            width: 100%;
            margin: 0 auto;
            position: relative;
        }

        /* Estilo para el thead */
        thead {
            background: #143b64;
            height: 30px; /* Altura de 30px */
        }

        /* Estilo para las celdas del thead */
        th {
            padding: 15px;
            text-align: left;
            color: white;
        }

        /* Estilo para las celdas del tbody */
        td {
            padding: 8px;
            /*text-align: left;*/
            border: 1px solid #ddd;
            text-align: center;
        }


       .cal_name td {
            padding: 12px 10px;
           font-size: 18px;
           font-weight: 700;

           text-align: left;
            /*background-color: #f5f5f5;*/
        }

        /* Estilo de filas alternas */
        .cal_name {
            background-color: #f5f5f5;
        }

    </style>
</head>
<body>
<div class="limiter">
    <table>
        <thead>
        <tr class="table100-head">
            <th>SEMANA</th>
            <th>LUNES<br>     <?=$dates[0]['date']?></th>
            <th>MARTES<br>    <?=$dates[1]['date']?></th>
            <th>MIERCOLES<br> <?=$dates[2]['date']?></th>
            <th>JUEVES<br>    <?=$dates[3]['date']?></th>
            <th>VIERNES<br>   <?=$dates[4]['date']?></th>
            <th>SABADO<br>    <?=$dates[5]['date']?></th>
            <th>DOMINGO<br>   <?=$dates[6]['date']?></th>
        </tr>
        </thead>
        <tbody>
        <tr class="cal_name">
            <td colspan="8"><?=$user['cat_name']?></td>
        </tr>
        <tr>
            <td style="text-align: left"><?=$user['name']?></td>
            <?php
                foreach($dates as $date){

                    if(isset($date['date'])){
                        if($date['schedule_type'] == 1){
                            //text
                            echo "<td style='background:{$date['color']} '>".mb_strtoupper($date['schedule_value'])."</td>";
                        }else{
                                //text
                                echo "<td>".$date['hour_start'].' - '.$date['hour_end']."</td>";
                        }
                    }else{
                        echo "<td>-</td>";
                    }

                }
            ?>

        </tr>

        </tbody>
    </table>
</div>
</body>
</html>