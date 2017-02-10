<?php
if (empty($head_title)) {
  $head_title = "L'actualité de la France Insoumise - JLM 2017";
}

if (empty($head_description)) {
  $head_description = "Agrégateur de flux concernant la France Insoumise. ";
}
?>
<!DOCTYPE html>
<html lang="fr" prefix="og: http://opengraphprotocol.org/schema/">
<head>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title><?php echo $head_title ?></title>
    <meta name="description" content="<?php echo $head_description ?>">
    <link rel="stylesheet" type="text/css" media="screen" title="Default" href="styles/base.css" />
</head>
<div class="column">
  <a name="top"></a>
  <div class="header home">
    <div class="fi"></div>
  </div>
