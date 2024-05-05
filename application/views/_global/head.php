<!DOCTYPE html>
<html lang="hu" class="h-100" data-bs-theme="dark">
<head>
    <base href="<?=base_url()?>" />
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <meta name="title" content="<?=($this->Seo->get('name',@$meta['name']) != null) ? $this->Seo->get('name') . " - " : ""?>446.hu" />
    <meta name="description" content="<?=($this->Seo->get('description',@$meta['description']) != null) ? $this->Seo->get('description') : ""?>" />
    <meta name="keywords" content="<?=($this->Seo->get('keywords',@$meta['keywords']) != null) ? $this->Seo->get('keywords') : ""?>" />
    <meta name="robots" content="<?=($this->Seo->get('robots',@$meta['robots']) != null) ? $this->Seo->get('robots') : ""?>" />
    <meta name="revisit-after" content="<?=($this->Seo->get('revisitAfter',@$meta['revisitAfter']) != null) ? $this->Seo->get('revisitAfter') : ""?>" />
    <meta name="author" content="Tajti Zoltán | tajtizoltan.hu">
    <meta name="copyright" content="Minden jog fenntartva - 446.hu" />
    <meta name="rating" content="general" />
    <meta name="distribution" content="local" />
    <meta name="language" content="HU" />
    <meta name="generator" content="446.hu">

    <meta property="og:type" content="website" />
    <meta property="og:url" content="<?=site_url()?>" />
    <meta property="og:title" content="<?=($this->Seo->get('name',@$meta['name']) != null) ? $this->Seo->get('name') . " - " : ""?>446.hu" />
    <meta property="og:description" content="<?=($this->Seo->get('description',@$meta['description']) != null) ? $this->Seo->get('description') : ""?>" />
    <meta property="og:image" content="<?=site_url()?>assets/images/446logo2.png" />
    
    <meta property="twitter:card" content="summary_large_image" />
    <meta property="twitter:url" content="<?=site_url()?>" />
    <meta property="twitter:title" content="<?=($this->Seo->get('name',@$meta['name']) != null) ? $this->Seo->get('name') . " - " : ""?>446.hu" />
    <meta property="twitter:description" content="<?=($this->Seo->get('description',@$meta['description']) != null) ? $this->Seo->get('description') : ""?>" />
    <meta property="twitter:image" content="<?=site_url()?>assets/images/446logo2.png" />
    <link rel="icon" type="image/png" href="./assets/images/favicon.png" />
    <link rel="canonical" href="<?=base_url()?>" />
	<link rel="stylesheet" media="screen" type="text/css" href="./assets/css/bootstrap.min.css" />
    <link rel="stylesheet" media="screen" type="text/css" href="./assets/css/fa.all.min.pro.css" />
    <?=@$css?>
    <link rel="stylesheet" media="screen" type="text/css" href="./assets/css/446style.css" />
    <title><?=(@$meta['name'] != "") ? $meta['name'] . " - " : ""?>446.HU - A Magyar PMR Csoport</title>
	<script type="application/ld+json">{"@context": "http://schema.org","@type": "Website","name": "446.HU - A Magyar PMR Csoport"}; </script>
	<script async src="https://www.googletagmanager.com/gtag/js?id=G-NSB0GJYLGN"></script> 
	<script> window.dataLayer = window.dataLayer || []; function gtag(){dataLayer.push(arguments);} gtag('js', new Date()); gtag('config', 'G-NSB0GJYLGN'); </script>
</head>
<body class="" <?=(uri_string() == "public/terkep") ? 'style="padding-top: 3.5rem;"' : ''?>>
