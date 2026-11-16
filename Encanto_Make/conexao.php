<?php

try {
    $conexao = new mysqli(
        getenv('DB_HOST') ?: 'localhost',
        getenv('DB_USER') ?: 'root',
        getenv('DB_PASSWORD') ?: '',
        getenv('DB_NAME') ?: 'encanto_make',
        (int) (getenv('DB_PORT') ?: 3306)
    );
    $conexao->set_charset('utf8mb4');
} catch (mysqli_sql_exception $erro) {
    error_log('Bela Agenda: falha na conexão com o banco.');
    http_response_code(503);
    exit('A agenda está indisponível. Tente novamente mais tarde.');
}

function consultar(mysqli $conexao, string $sql, array $parametros): mysqli_stmt|false {
    try {
        $consulta = $conexao->prepare($sql);
        $consulta->execute($parametros);
        return $consulta;
    } catch (mysqli_sql_exception $erro) {
        error_log('Bela Agenda: falha ao executar uma consulta.');
        if (str_starts_with($sql, 'SELECT')) {
            http_response_code(503);
            exit('A agenda está indisponível. Tente novamente mais tarde.');
        }

        return false;
    }
}
