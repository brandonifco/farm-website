<?php session_start(); ?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Farm</title>
    <meta name="description" content="Welcome to our regenerative farm. Shop natural products, learn, and visit.">
    <link rel="preload" as="image"
        href="/assets/images/hero_2048.avif"
        type="image/avif"
        imagesrcset="
        /assets/images/hero_640.avif   640w,
        /assets/images/hero_1024.avif 1024w,
        /assets/images/hero_1536.avif 1536w,
        /assets/images/hero_2048.avif 2048w,
        /assets/images/hero_2560.avif 2560w"
        imagesizes="100vw">
    <link rel="stylesheet" href="/css/base.css">
    <link rel="stylesheet" href="/css/layout.css">
    <link rel="stylesheet" href="/css/components.css">
    <link rel="stylesheet" href="/css/responsive.css">
</head>

<body>
    <?php include 'nav.php'; ?>
    <main>