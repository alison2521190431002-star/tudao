<?php

namespace App\DAO;

use App\Config\Conexao;
use PDO;

class PessoaDAO
{
    private PDO $conexao;

    public function __construct()
    {
        $this->conexao = Conexao::conectar();
    }

    public function listar(): array
    {
        $sql = "
            SELECT *
            FROM pessoas
            ORDER BY nome
        ";

        $stmt = $this->conexao->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function buscarPorId(int $id): ?array
    {
        $sql = "
            SELECT *
            FROM pessoas
            WHERE id = :id
        ";

        $stmt = $this->conexao->prepare($sql);

        $stmt->bindValue(
            ':id',
            $id,
            PDO::PARAM_INT
        );

        $stmt->execute();

        $pessoa = $stmt->fetch(PDO::FETCH_ASSOC);

        return $pessoa ?: null;
    }

    public function buscarPorNome(string $nome): ?array
    {
        $sql = "
            SELECT *
            FROM pessoas
            WHERE nome = :nome
            LIMIT 1
        ";

        $stmt = $this->conexao->prepare($sql);

        $stmt->bindValue(
            ':nome',
            $nome
        );

        $stmt->execute();

        $pessoa = $stmt->fetch(PDO::FETCH_ASSOC);

        return $pessoa ?: null;
    }

    public function pesquisarPaginado(
        string $nome,
        int $pagina,
        int $porPagina = 5
    ): array {

        $offset = ($pagina - 1) * $porPagina;

        $sql = "
            SELECT *
            FROM pessoas
            WHERE nome LIKE :nome
            ORDER BY nome
            LIMIT :limite OFFSET :offset
        ";

        $stmt = $this->conexao->prepare($sql);

        $stmt->bindValue(
            ':nome',
            '%' . $nome . '%'
        );

        $stmt->bindValue(
            ':limite',
            $porPagina,
            PDO::PARAM_INT
        );

        $stmt->bindValue(
            ':offset',
            $offset,
            PDO::PARAM_INT
        );

        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function contarPesquisa(string $nome): int
    {
        $sql = "
            SELECT COUNT(*)
            FROM pessoas
            WHERE nome LIKE :nome
        ";

        $stmt = $this->conexao->prepare($sql);

        $stmt->bindValue(
            ':nome',
            '%' . $nome . '%'
        );

        $stmt->execute();

        return (int) $stmt->fetchColumn();
    }
}