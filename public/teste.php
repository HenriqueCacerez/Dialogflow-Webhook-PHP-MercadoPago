<?php

    $whatsApp = "+55 18 996822218";

    // remover espaços e traços do número de telefone
    $numeroTelefone = preg_replace('/\D/', '', $whatsApp);

    // verificar se o número de telefone tem um formato válido
    echo preg_match('/^\+?\d{10,15}$/', $numeroTelefone) ? "valido" : "n valido";