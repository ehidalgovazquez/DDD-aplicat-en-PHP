<?php

    use Doctrine\ORM\EntityManagerInterface;

    $entityManager = require __DIR__.'/../../../config/doctrine.php';

    return $entityManager;