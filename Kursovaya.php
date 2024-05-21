<?php 
//Файл данных
$filename = "data.txt";  
//Высота изображения
$height = $_GET['h'];
//Ширина изображения
$width = $_GET['w']; 
// Жирность точки
$point = 3;
//Считываем содержимое файла
if (!is_file($filename)) exit("Отсутсвует файл данных");
$arr = file($filename);
//Записываем значения x и y в массив
$arr_x = array();
$arr_y = array();
// Определяем количество строк в файле
$number_lines= count(file($filename));
 foreach($arr as $line)
  {
//Данные в текстовом файле записываем через ";"
    $num = preg_split("/[\;]+/",$line);
//Добавляем элементы в конец массива
	array_push($arr_x, $num[0]);
	array_push($arr_y, $num[1]);
  }
//Находим коэффициенты для X
$min_x = min($arr_x);
$max_x = max($arr_x);
//Находим максимальную точку по модулю (ox)
$max_x=((abs($min_x) > abs($max_x))?(abs($min_x)):abs($max_x));
//Находим коэффициенты для Y
$min_y = min($arr_y);
$max_y = max($arr_y);
 //Максимальная точка по модулю (OY)
$max_y=((abs($min_y) > abs($max_y))? (abs($min_y)):abs($max_y));
 // Расчитаем максимальное значение по оси ординат 
$height_max = $height / 2;
 // Расчитаем максимальное значение по оси абсцисс
$width_max = $width / 2;
// Находим коэффициент
$koef_x =$width_max/$max_x;
$koef_y =$height_max/$max_y;
  // Рисуем диаграмму 
$img = imageCreate($width + 10,$height + 5);
if (!$img) exit("Не удалось создать изображение");
// Формируем и создаем цвета
$white = imagecolorallocate($img, 255, 255, 255);
$black = imagecolorallocate($img, 0, 0, 0);
  // Фон делаем белым
imagefill($img, 1, 1, $white); 
  // Рисуем оси
imageLine($img, 0, $height_max, $width, $height_max, $black);
imageLine($img, $width_max, 5, $width_max, $height, $black);
  // Вычисляем положение точки на графике
for ($i=0; $i < $number_lines; $i++)
  {
	$image_x = (float)($width*($arr_x[$i]*$koef_x - $width/2*(-1))/($width_max - $width/2*(-1)))+5;
	$image_y = (float)($height*($height_max - $arr_y[$i]*$koef_y)/($height_max- $height/2*(-1)))+5;
// Рисуем точку
 imagefilledrectangle($img, $image_x - $point, $image_y - $point,  $image_x + $point, $image_y + $point, $black);
  }
 //Создаем текст для оси
 $txt_X = "$max_x"; 
 $txt_Y = "$max_y"; 
// Функции нанесения текста
  imagestring($img, 1, $width - 10, $height_max + 5, $txt_X, $black);
  imagestring($img, 1, $width_max - 9, 0, $txt_Y, $black);
  imagestring($img, 1, $width_max - 7, $height_max+ 3, '0', $black);
//Сохраняем png изображение
imagepng($img, "2.png");
//Выводим изображение
echo ('<img src="2.png">');
?>