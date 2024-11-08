<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<style>
h1{
    display: flex;
   justify-content: center;
}
.sms{
    display: flex;
    flex-direction: column;
}
.lot{
    display: flex;
    justify-content: center;
    font-size: 20px;
}

</style>
<body>
    <form method="post" action="">
        <a>номер торгов</a>
        <input type="text" id="number" name="number">
        <a>номер лота</a>
        <input type="text" id="lot" name="lot">
        <input type="submit" value="Найти ">
    </form>
    <?php
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
    include 'simple_html_dom.php';
    $db_host = 'localhost';
    $db_user = 'root';
    $db_password = '';
    $db_name = 'lots';
    $mysqli = new mysqli($db_host, $db_user, $db_password, $db_name);
    //проверка соединения
    if ($mysqli->connect_errno) {
        echo "Не удалось подключиться к бд" . $mysqli->connect_error;
    } else {
        echo "Все хорошо";
    }
    //данные с формы
    @$number = $_POST["number"];
    @$lot = $_POST["lot"];

    //получение данных с сайта

    $context = stream_context_create([
        'http' => [
            'header' => "User-Agent: Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/50.0.2661.102 Safari/537.36"
        ],
        'ssl' => [
            'verify_peer' => false,
            'verify_peer_name' => false
        ]
    ]);

    $pattern = "Лот № " . $lot; //чтобы человек искал просто по 1 цифре
    $check = array(); // массив для проверки
    $values = array(); //МАССИВ для хранения всех данных
    $values[] = "'$pattern'";
    $check[] = "'$pattern'";
    $content = file_get_contents("https://nistp.ru", false, $context);
    $html = str_get_html($content);
    $found = false; //флаг для нахождения лота и тдтптомусему
    foreach ($html->find('td') as $table) {
        if ($table->plaintext == $number) {
            $parent = $table->parent(); //поиск только внутри нужной нам строки
            $spans = $parent->find('span');
            $ah = $parent->find('a');
            //2 цикл для поиска номера лота
            foreach ($spans as $span) {
                if (stristr($span->plaintext, $pattern) !== false) {
                    echo $table->plaintext;
                    echo $span->plaintext;
                    //получаем ссылку лота
                    foreach ($ah as $a) {
                        $lotLink = $a->getAttribute('href');
                        $values[] = "'$lotLink'"; //первое 3начение
                        $check[] = "'$lotLink'";
                        break;
                    }
                    $found = true;
                    break;
                }
            }
        }
        if ($found) {
            break;
        }
    }
    if (!$found) {
        echo 'Лот не найден';
    } else {
        echo "<br>";
        echo "Ссылка на лот: " . $lotLink;
    }
    echo "<br>";
    $contentlot = file_get_contents($lotLink, false, $context);
    $lotlot = str_get_html($contentlot);

    foreach ($lotlot->find('table') as $table1) {
        $ths = $table1->find('th');
        foreach ($ths as $th) {
            if (stristr($th->plaintext, $pattern) !== false) {
                // Описание имущества и начальная цена
                foreach ($table1->find('tr') as $tr) {
                    $tds = $tr->find('td');
                    foreach ($tds as $key => $td) {
                        if (stristr($td->plaintext, 'Cведения об имуществе (предприятии) должника, выставляемом на торги, его составе, характеристиках, описание') !== false) {
                            $nextTd = $tds[$key + 1];
                            $targetDescription = $nextTd->plaintext;
                            echo "Описание имущества: " . $targetDescription . "<br>";
                            $values[] = "'$targetDescription'";
                        }
                        if (stristr($td->plaintext, 'Начальная цена') !== false) {
                            $nextTd = $tds[$key + 1];
                            $targetPrice = $nextTd->plaintext;
                            echo "Начальная цена: " . $targetPrice;
                            $values[] = "'$targetPrice'"; //3 и 4 3начение
                        }
                    }
                }
                break 2;
            }
        }
    }
    echo "<br>";
    foreach ($lotlot->find('table') as $table2) {
        $ths1 = $table2->find('th');
        foreach ($ths1 as $th1) {
            if (stristr($th1->plaintext, 'Контактное лицо организатора торгов') !== false) {
                // почта и телефон
                foreach ($table2->find('tr') as $tr1) {
                    $tds1 = $tr1->find('td');
                    foreach ($tds1 as $key1 => $td1) {
                        if (stristr($td1->plaintext, 'E-mail') !== false) {
                            $nextTd = $tds1[$key1 + 1];
                            $email = $nextTd->plaintext;
                            echo "E-mail: " . $email . "<br>";
                            $values[] = "'$email'";
                        }
                        if (stristr($td1->plaintext, 'Телефон') !== false) {
                            $nextTd = $tds1[$key1 + 1];
                            $number1 = $nextTd->plaintext;
                            echo "Телефон: " . $number1 . "<br>";
                            $values[] = "'$number1'"; //5 и 6 3начение
                        }
                    }
                }
                break 2;
            }
        }
    }
    foreach ($lotlot->find('table') as $table2) {
        $ths1 = $table2->find('th');
        foreach ($ths1 as $th1) {
            if (stristr($th1->plaintext, 'Информация о должнике') !== false) {
                // ИНН и номер дела
                foreach ($table2->find('tr') as $tr1) {
                    $tds1 = $tr1->find('td');
                    foreach ($tds1 as $key1 => $td1) {
                        if (stristr($td1->plaintext, 'ИНН') !== false) {
                            $nextTd1 = $tds1[$key1 + 1];
                            $inn = $nextTd1->plaintext;
                            echo "ИНН: " . $inn . "<br>";
                            $values[] = "'$inn'";
                        }
                        if (stristr($td1->plaintext, 'Номер дела о банкротстве') !== false) {
                            $nextTd1 = $tds1[$key1 + 1];
                            $number2 = $nextTd1->plaintext;
                            echo "Номер дела о банкротстве: " . $number2 . "<br>";
                            $values[] = "'$number2'"; //7 и 8 3начение
                        }
                    }
                }
                break 2;
            }
        }
    }
    $placeholderAdded = false;
    foreach ($lotlot->find('table') as $table2) {
        $ths1 = $table2->find('th');
        foreach ($ths1 as $th1) {
            if (stristr($th1->plaintext, 'Информация о ходе торгов') !== false) {
                // Даты
                foreach ($table2->find('tr') as $tr1) {
                    $tds1 = $tr1->find('td');
                    foreach ($tds1 as $key1 => $td1) {
                        if (stristr($td1->plaintext, 'Дата начала представления заявок на участие') !== false) {
                            $nextTd1 = $tds1[$key1 + 1];
                            $date = $nextTd1->plaintext;
                            echo "Дата начала: " . $date . "<br>";
                            $values[] = "'$date'";
                        }
                        if (stristr($td1->plaintext, 'Дата проведения') !== false) {
                            $nextTd1 = $tds1[$key1 + 1];
                            $date1 = $nextTd1->plaintext;
                            echo "Дата проведения: " . $date1 . "<br>";
                            $values[] = "'$date1'"; //9 и 10 3начение
                        }
                    }
                }
                break 2;
            }
        }
    }
    while (count($values) < 10) {
        $values[] = "NULL";
    }
    $checkString = implode(",", $check);
    $valuesString = implode(",", $values);
    // Получаем значения для полей 
    list($pattern, $url) = explode(",", $checkString, 2);
    $pattern = trim($pattern, "'");
    $url = trim($url, "'");
    //Проверяем, существует ли запись с такими же значениями 

    $regular = "/'([^']+)'/";
    preg_match_all($regular, $valuesString, $matches);
    $r1 = $matches[1][0];//Номер лота
    $r2 = $matches[1][1];//URL
    $r3 = $matches[1][2];//Описание
    $r4 = $matches[1][3];//Цена
    $r5 = $matches[1][4];//Телефон
    $r6 = $matches[1][5];//Email
    $r7 = $matches[1][6];//Инн
    $r8 = $matches[1][7];//Номер дела о банкротствеа
    $r9 = $matches[1][8];
    $r10 = $matches[1][9];
    if ($r10 === "NULL") {
        $r10 = null;
    }   
    $checkExistingQuery = "SELECT COUNT(*) AS count FROM lot WHERE numberlot = '$r1' AND url = '$r2'";
    $result = $mysqli->query($checkExistingQuery);
    $row = $result->fetch_assoc();
    $count = $row['count'];
    if ($count > 0) {
        // Если запись существует, обновляем её
        $updateQuery = "UPDATE lot SET Description = '$r3', Price = '$r4', number = '$r5', email = '$r6', inn = '$r7', casenumber = '$r8', date1 = '$r9', date2 = '$r10' WHERE numberlot = '$r1' AND url = '$r2'";
        if ($mysqli->query($updateQuery) === TRUE) {
            echo "Запись успешно обновлена";
        } else {
            echo "Ошибка при обновлении записи: " . $mysqli->error;
        }
    } else {
        // Если запись не существует, выполняем вставку новой записи
        $insertQuery = "INSERT INTO lot (numberlot, url, Description, Price, number, email, inn, casenumber, date1, date2) VALUES ($valuesString)"; 
        if ($mysqli->query($insertQuery) === TRUE) {
            echo "Запись успешно добавлена";
        } else {
            echo "Ошибка при добавлении записи: " . $mysqli->error;
        }
    }
    echo "<br><h1>Все записи</h1>";
    $all=mysqli_query($mysqli,"SELECT * FROM lot");
   
    while ($result = mysqli_fetch_array($all)) {
        $numberlot=$result['numberlot'];
        $url=$result['url'];
        $Description=$result['Description'];
        $Price=$result['Price'];
        $email=$result['email'];
        $number=$result['number'];
        $inn=$result['inn'];
        $casenumber=$result['casenumber'];
        $date1=$result['date1'];
        $date2=$result['date2'];
        echo" <div class='sms'>";
        echo "
            <a class='lot'>".$numberlot."</a>
            <a>ссылка на лот: ".$url."</a>
            <a>описание: ".$Description."</a>
            <a>Цена: ".$Price."</a>
            <a>email контактного лица: ".$email."</a>
            <a>Номер контактного лица: ".$number."</a
            <a>ИНН должника: ".$inn."</a>
            <a>Номер дела о банкротстве: ".$casenumber."</a>
            <a> Дата начала: ".$date1."</a>
            <a> Дата Проведения: ".$date2."</a>
        ";
        echo " </div>";
        echo "<br>";
    }
   





    // $all=mysqli_query($mysqli,"SELECT * FROM lot");
   
    // while ($result = mysqli_fetch_array($all)) {
    //     echo" <div class='sms'>";
    //     echo "<a>{$result['numberlot']}</a> ссылка на лот: {$result['url']} описание: {$result['Description']} Цена:{$result['Price']} email контактного лица:{$result['email']} номер контактного лица:{$result['number']} Инн должника:{$result['number']} Номер дела о банкротстве:{$result['casenumber']} Дата начала:{$result['date1']}  Дата проведения:{$result['date2']}";
    //     echo " </div>";
    //     echo "<br>";
    // }
    ?>
    
</body>

</html>
