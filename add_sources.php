<?php
include 'config/load.php';

$Source = new Source();
$Source->setTitle('Blog JLM');
$Source->setType(SOURCE_TYPE_RSS);
$Source->setUrl('http://melenchon.fr/categorie/tous-les-articles/feed/');
$Source->setImg_url('test.png');
$Source->setOnline(1);
$Source->save();

$Source = new Source();
$Source->setTitle('Chaine YT JLM');
$Source->setType(SOURCE_TYPE_YT);
$Source->setUrl('https://www.youtube.com/playlist?list=PLnAm9o_Xn_3CzSZhVkM6dG2adkfyp8QZ_');
$Source->setImg_url('test2.png');
$Source->setOnline(1);
$Source->save();