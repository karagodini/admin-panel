<?php
session_start();
$pass='password'; // Пароль для входа в CMS
$adm=0; // Если в переменной $adm==1 то мы успешно авторизованы

if((isset($_POST['slovo'])||isset($_POST['sekret']))||($_SESSION['sekret']==md5($pass))){
	if (($_POST['slovo']==$pass)||($_SESSION['sekret']==md5($pass))){
		$_SESSION['sekret']=md5($pass); // Если пароль совпадает добавляем в сессию переменную secret с его md5 хэшем
		$adm=1;
		};
		} else {
			// Если пароля нет показываем форму входа
			echo('
			<!doctype html>
			<html lang="ru">
			<head>
			<meta charset="UTF-8">
			<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
			<meta name="viewport" content="width=device-width, initial-scale=1">
			</head>
			<body>
			<center><form method="POST" action="admin.php" style="margin-top: 30px;">
			<div style="color: black; width: 500px; height: 100px; line-height: 100px; font-size: 24px; letter-spacing: 1px;">Панель администратора</div>
			<input type="text" placeholder="Введите пароль" name="slovo" size="100" style="margin-top: 10px; color: black; font-size: 18px; width: 250px; height: 30px; line-height: 30px; border-radius: 5px; border: 1px solid; padding: 0 10px;"><br>
			<input type=submit name="save" value="Войти в систему" style="background: #FFFFFF; width: 250px; margin-top: 10px; font-size: 16px; display: block; height: 37px; line-height: 25px; text-decoration: none; cursor: pointer; letter-spacing: 0.5px; vertical-align: middle; text-align: center; border: 2px solid #000000; border-radius: 5px;">
			</form></center></body></html>');
		};

if($adm==1){
if(isset($_POST['pagename'])){
	$_SESSION['pagename']=$_POST['pagename']; // Получаем имя страницы для редактирования
};	
if(isset($_SESSION['pagename'])){	
	$pagename=$_SESSION['pagename'];
} else {
	$pagename='index.html';	// Если его нет в куках и нет в POST запросе то ставим его=index.html	
};

// В переменную $template поместим код редактируемой странички
$template=file_get_contents($pagename);

// Выводим шапку админки
echo('
<html>
<head>
<style>
	body, html {
	padding: 0px; margin: 0px;
	background: #eee; 
	text-align: center;
	display: flex;
    align-items: flex-start;
}

textarea {
	padding: 20px; 
	width: 600px; 
	height: 400px;
	background: rgb(0, 0, 0);
    color: rgb(255, 255, 255);
    border-radius: 10px;
}
a {
	text-decoration: none;
}
.kartinka {
	display: inline-block; 
	text-decoration: none;
	padding: 20px; padding-bottom: 5px;
	text-align: center; 
	cursor: pointer;
}
.kartinka:hover {
	background: #0f172a; 
	color: #FFF;
	border-radius: 5px;
}
.kartinka img {
	height: 100px; 
	margin-bottom: 10px;
}
.bigkartinka {
	height: 300px; 
	padding: 50px;
}
#menu {
	background: #0f172a;
	padding-left: 10px;
	height: 100%;
	width: 200px;
	line-height: 50px;
	text-align: center;
	font-size: 20px;
	display: flex;
	flex-direction: column;
    align-items: flex-start;
	position: fixed;
}
#myform {
	height: 40px; 
	line-height: 40px;
	display: inline-block;
	vertical-align: top;
	padding-left: 20px; 
	padding-right: 20px;
	margin-top: 20px;
	text-align: center;
	font-size: 90%;
}
#menu a {
	height: 40px; line-height: 40px;
	text-decoration: none;
	display: inline-block;
	vertical-align: top;
	padding-left: 20px; padding-right: 20px;
	color: white;
	margin-right: 3px;
	margin-top: 40px;
	text-align: left;
	width: 150px;
	font-size: 90%;
}
.btn-menu:hover {
	background: #64748b;
	border-radius: 5px;
}

.btn-menu.active {
	color: #000 !important;
}

.mytext, .cssjs {
	display: block;
	border-radius: 5px;
	padding: 10px; padding-left: 20px; padding-right: 20px;
	margin: 20px;
	background: #fffff9;
	color: black;
}
.mytext:hover, .cssjs:hover {
	background: #0f172a;
	color: #FFF;
	cursor: pointer;
}
#help {
	max-width: 700px; 
	text-align: left; 
	font-size: 120%;
	padding-left: 50px;
}

.css-block {
}

.images {
	margin-left: 220px;
	display: flex;
	flex-wrap: wrap;
}

</style>
</head>
<body>
<div id="menu">
<form action="admin.php" id="myform" method="POST">
<select name="pagename">');
// Создаем список страниц в корневой папке доступных для редактирования
$filelist1 = glob("*.html");
$ddd=0;
$ssss='';
for ($j=0; $j<count($filelist1); $j++) {
	if($filelist1[$j]==$_SESSION['pagename']){
		$ssss.=('<option selected>'.$filelist1[$j].'</option>');
		$ddd=1;
	} else {
		$ssss.=('<option>'.$filelist1[$j].'</option>');
	};
};
if($ddd==0){
	$ssss='';
	for ($j=0; $j<count($filelist1); $j++) {
		if($filelist1[$j]=='index.html'){
			$ssss.=('<option selected>'.$filelist1[$j].'</option>');
			$ddd=1;
		} else {
			$ssss.=('<option>'.$filelist1[$j].'</option>');
		};
	};
};
echo($ssss);
echo('</select>
<input type="submit" value="Редактировать" title="Редактировать" style="padding: 5px 20px; margin-top: 10px; border-radius: 5px;  background: #FFFFFF; cursor: pointer;">
</form>
<a class="btn-menu" href="admin.php?mode=0">Тексты</a>
<a class="btn-menu" href="admin.php?mode=7">Картинки</a>
<a class="btn-menu" href="admin.php?mode=5">HTML</a>
<a class="btn-menu" href="admin.php?mode=8">CSS и JS</a>
<a class="btn-menu" href="index.html" target="_blank">На сайт</a>
<a class="btn-menu" href="admin.php">Помощь</a>
</div>
');




//******************************************************************************************
// Список картинок
echo('<div class="images">');
if($_GET['mode']=='7'){
	// Вытаскиваем список картинок из HTML кода
	$imgreg = "/[\"|\(']((.*\\/\\/|)([\\/a-z0-9_%]+\\.(jpg|png|gif)))[\"|\)']/";
	preg_match_all($imgreg, $template, $imgmas);
	for ($j=0; $j< count($imgmas[1]); $j++) {
		$imgname=trim($imgmas[1][$j]);
		echo('<div class="kartinka"><a href="admin.php?mode=1&img='.$imgname.'"><img src="'.$imgname.'?'.rand(1, 32000).'"></a><br>'.$imgname.'<br>');
		if(file_exists($imgname)){
			$size = getimagesize ($imgname); echo "Размер картинки: $size[0] * $size[1]"."<p>";
		} else { echo("Картинка не загружена"); };
		echo("</div>");
	};
	// Получаем список CSS файлов в массив $mycss	
	$mycss = array();
	$cssreg = "/[\"']((.*\\/\\/|)([\\/a-z0-9_%]+\\.(css)))[\"']/"; 
	preg_match_all($cssreg, $template, $cssmas);
	for ($j=0; $j< count($cssmas[1]); $j++) {
		array_push($mycss, trim($cssmas[1][$j]));
	};
	// Вытаскиваем с каждого CSS файла адреса картинок
	for ($i=0; $i< count($mycss); $i++) {
		$template=file_get_contents($mycss[$i]);
		$imgreg = "/[.\(]((.*\\/\\/|)([\\/a-z0-9_%]+\\.(jpg|png|gif)))[\)]/"; 
		preg_match_all($imgreg, $template, $imgmas);
		for ($j=0; $j< count($imgmas[1]); $j++) {
			$imgname=trim($imgmas[1][$j]);
			echo('<div class="kartinka"><a href="admin.php?mode=1&img='.$imgname.'"><img src="'.$imgname.'?'.rand(1, 32000).'"></a>'.$imgname.'<br>');
			if(file_exists($imgname)){
				$size = getimagesize ($imgname); echo "Размер картинки: $size[0] * $size[1]"."<p>";
			} else { 
				if(file_exists(substr($imgname,1))){
					$size = getimagesize(substr($imgname,1)); echo "Размер картинки: $size[0] * $size[1]"."<p>";
				} else { 
					echo("Картинка не загружена"); 
				};		
			};
			echo("</div>");
		};
	};
};
echo('</div>');

//******************************************************************************************
// Одна картинка
if($_GET['mode']=='1'){
	$imgname=$_GET['img'];
	if($imgname[0]=='/'){
		$imgname=substr($imgname,1);
	};
	echo('<center><img src="'.$imgname.'" class="bigkartinka"><br>'.$imgname.'<p>');
	if(file_exists($imgname)){
		$size = getimagesize ($imgname); echo "Размер картинки: $size[0] * $size[1]"."<p>";
	} else { 
		if(file_exists(substr($imgname,1))){
			$size = getimagesize(substr($imgname,1)); echo "Размер картинки: $size[0] * $size[1]"."<p>";
		} else { 
			echo("Картинка не загружена"); 
		};		
	};
	echo('<form enctype="multipart/form-data" action="admin.php?mode=2&img='.$imgname.'" method="POST">Загрузить картинку с компьютера: <p><input name="userfile" type="file" required><p><input type="submit" style="width: 250px; height: 40px;" value="Начать загрузку" /></form>');	
};


//******************************************************************************************
// Замена картинки
if($_GET['mode']=='2'){
	$imgname=$_GET['img'];
	if (move_uploaded_file($_FILES['userfile']['tmp_name'], $imgname)) {
		echo "<br><br><center>Файл был успешно загружен.<p><a href='admin.php'>Вернуться к списку картинок</a><p>ПРИ ПРОСМОТРЕ ИЗМЕНЕНИЙ НА САЙТЕ НЕ ЗАБУДЬТЕ ОБНОВИТЬ ЕГО СТРАНИЦУ В БРАУЗЕРЕ";
	};
};


//******************************************************************************************
// Список текстовых фрагментов
echo('<div class="block-teksti">');
if($_GET['mode']=='0'){
	// Помещаем в массив $ff все тексты из HTML кода
	$ff=array(); $content=preg_replace('/<[^>]+>/', '^', $template); $teksta = explode('^', $content);
	for ($j=0; $j< count($teksta); $j++) { if(strlen(trim($teksta[$j]))>1) $ff[]=(trim($teksta[$j])); };
	for ($j=0; $j< count($ff); $j++) { 
		echo('<a href="admin.php?mode=3&j='.$j.'" class="mytext">'.$ff[$j].'</a>');
	};
};
echo('</div>');


//******************************************************************************************
// Текстовый фрагмент
if($_GET['mode']=='3'){
	// Помещаем в массив $ff все текстовые фрагменты из HTML кода
	$ff=array(); $content=preg_replace('/<[^>]+>/', '^', $template); $teksta = explode('^', $content);
	for ($j=0; $j< count($teksta); $j++) { if(strlen(trim($teksta[$j]))>1) $ff[]=(trim($teksta[$j])); };
	$jj=$_GET['j'];
	$tektekst=$ff[$jj];
	$kol=1;
	for ($j=0; $j<$jj; $j++) { 
		$kol=$kol + substr_count($ff[$j],$tektekst);
	};
	echo('<div style="margin: 0 auto; text-align: center;"><form method="POST" action="admin.php?mode=4&j='.$jj.'"><br><br><h2>Редактирование текстового фрагмента</h2><br><br><textarea name="mytext">'.$tektekst.'</textarea><br><input style="width: 600px; margin-top: 10px; height: 50px;" type="submit" value="Сохранить" title="Сохранить"></form></div>');
};


//******************************************************************************************
// Редактирование текстового фрагмента
if($_GET['mode']=='4'){
	// Помещаем в массив $ff все текста из HTML кода
	$ff=array(); $content=preg_replace('/<[^>]+>/', '^', $template); $teksta = explode('^', $content);
	for ($j=0; $j< count($teksta); $j++) { if(strlen(trim($teksta[$j]))>1) $ff[]=(trim($teksta[$j])); };
	$jj=$_GET['j'];
	$tektekst=$ff[$jj];
	$kol=1;
	for ($j=0; $j<$jj; $j++) { 
		$kol=$kol + substr_count($ff[$j],$tektekst);
	};
	$subject=file_get_contents($pagename);
	function str_replace_nth($search, $replace, $subject, $nth)
	{
		$found = preg_match_all('/'.preg_quote($search).'/', $subject, $matches, PREG_OFFSET_CAPTURE);
		if (false !== $found && $found > $nth) {
			return substr_replace($subject, $replace, $matches[0][$nth][1], strlen($search));
		}
		return $subject;
	};
	$rez=str_replace_nth($tektekst, $_POST['mytext'], $subject, $kol-1);
	file_put_contents($pagename, $rez);
	echo "<br><br><center>Текст был успешно изменен.<p><a href='admin.php?mode=0'>Вернуться к списку текстов</a><p>ПРИ ПРОСМОТРЕ ИЗМЕНЕНИЙ НА САЙТЕ НЕ ЗАБУДЬТЕ ОБНОВИТЬ ЕГО СТРАНИЦУ В БРАУЗЕРЕ";
};


//******************************************************************************************
// Форма для HTML кода
if($_GET['mode']=='5'){
	$template=htmlspecialchars(file_get_contents($pagename));
	echo('<div style="text-align: center;"><form method="POST" action="admin.php?mode=6"><br><br><h2>Редактирование HTML кода</h2><br><br><textarea name="mytext" style="width: 1000px; height: 500px;">'.$template.'</textarea><br><input style="width: 30%; margin-top: 10px; height: 50px;" type="submit" value="Сохранить" title="Сохранить"></form></div>');
};


//******************************************************************************************
//Редактирование HTML кода
if($_GET['mode']=='6'){
	file_put_contents($pagename, $_POST['mytext']);
};

//******************************************************************************************
// Получаем список CSS и JS файлов
echo('<div class="cssjs-block">');
if($_GET['mode']=='8'){
	echo('<br><h2>CSS и JS файлы относящиеся к '.$pagename.'</h2><p><br>');
	$cssreg = "/[\"']((.*\\/\\/|)([\\/a-z0-9_%]+\\.(css)))[\"']/"; 
	preg_match_all($cssreg, $template, $cssmas);
	for ($j=0; $j< count($cssmas[1]); $j++) {
	$rrr=trim($cssmas[1][$j]);
	if (!(strstr($rrr, "http"))) {
 	echo('<a class="cssjs" href="admin.php?mode=9&fl='.$rrr.'">'.$rrr.'</a><p>');
	};
	};
	$cssreg = "/[\"']((.*\\/\\/|)([\\/a-z0-9_%]+\\.(js)))[\"']/"; 
	preg_match_all($cssreg, $template, $cssmas);
	for ($j=0; $j< count($cssmas[1]); $j++) {
	$rrr=trim($cssmas[1][$j]);
	if (!(strstr($rrr, "http"))) {
	echo('<a class="cssjs" href="admin.php?mode=9&fl='.$rrr.'">'.$rrr.'</a><p>');
	};
	};
};
echo('');

//******************************************************************************************
// Форма для HTML кода
if($_GET['mode']=='9'){
	$template=htmlspecialchars(file_get_contents($_GET['fl']));
	echo('<div style="text-align: center;"><form method="POST" action="admin.php?mode=10&fl='.$_GET['fl'].'"><br><br><h2>Редактирование кода</h2><br><br><textarea name="mytext" style="width: 1000px; height: 500px;">'.$template.'</textarea><br><input style="width: 30%; margin-top: 10px; height: 50px;" type="submit" value="Сохранить" title="Сохранить"></form></div>');
};

//******************************************************************************************
//Редактирование всего HTML кода
if($_GET['mode']=='10'){
	file_put_contents($_GET['fl'], $_POST['mytext']);
};

//******************************************************************************************
// Помощь
if(!isset($_GET['mode'])){
	echo('<div id="help">
	<p>
	<br>
	<h2>Adminka (версия 0.1)</h2>
	<p>Данная административная панель состоит всего из одного файла admin.php и предназначена для управления уже готовыми лэндингами, состоящими из HTML страницы, и подключенных к ней CSS файлов.
	<p>	С помощью данной административной панели вы можете редактировать текста, и заменять картинки, изменять HTML код, JS и CSS вашего лэндинга.
	<p>Административная панель не требует установки, достаточно положить ее файл в папку рядом с файлом index.html
	<p>Разработано в 2020 году Иваном Карагодиным (<a href="https://ikaragodin.ru/">ikaragodin.ru</a>) в качестве мини-админки для лэндингов.
	</div>');
};

echo('</body></html>');
};
?>