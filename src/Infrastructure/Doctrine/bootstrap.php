<?php

    use Doctrine\ORM\EntityManagerInterface;

    $entityManagerFactory = require __DIR__.'/../../../config/doctrine.php';
    
    $entityManager=$entityManagerFactory();

    return $entityManager;