<?php

namespace App\DAO;

use App\Config\Conexao;
use PDO;

class MovimentacaoDAO
{
    private PDO $conexao;

    public function __construct()
    {
        $this->conexao = Conexao::conectar();
    }

    // =====================================================
    // DEPOSITAR
    // =====================================================

    public function depositar(
        int $idPessoa,
        float $valor,
        string $observacao
    ): bool {

        $sql = "
            INSERT INTO movimentacao
            (
                idPessoa,
                Credito,
                Debito,
                DataOperacao,
                Observacao
            )
            VALUES
            (
                :idPessoa,
                :credito,
                NULL,
                :dataOperacao,
                :observacao
            )
        ";

        $stmt = $this->conexao->prepare($sql);

        $stmt->bindValue(
            ':idPessoa',
            $idPessoa,
            PDO::PARAM_INT
        );

        $stmt->bindValue(
            ':credito',
            $valor
        );

        $stmt->bindValue(
            ':dataOperacao',
            date('Y-m-d')
        );

        $stmt->bindValue(
            ':observacao',
            $observacao
        );

        return $stmt->execute();
    }

    // =====================================================
    // SALDO
    // =====================================================

    public function saldo(int $idPessoa): float
    {
        $sql = "
            SELECT
                COALESCE(SUM(Credito), 0)
                -
                COALESCE(SUM(Debito), 0)
            FROM movimentacao
            WHERE idPessoa = :idPessoa
        ";

        $stmt = $this->conexao->prepare($sql);

        $stmt->bindValue(
            ':idPessoa',
            $idPessoa,
            PDO::PARAM_INT
        );

        $stmt->execute();

        return (float) $stmt->fetchColumn();
    }

    // =====================================================
    // SACAR
    // =====================================================

    public function sacar(
        int $idPessoa,
        float $valor,
        string $observacao
    ): bool {

        $saldo = $this->saldo($idPessoa);

        if ($valor > $saldo) {
            return false;
        }

        $sql = "
            INSERT INTO movimentacao
            (
                idPessoa,
                Credito,
                Debito,
                DataOperacao,
                Observacao
            )
            VALUES
            (
                :idPessoa,
                NULL,
                :debito,
                :dataOperacao,
                :observacao
            )
        ";

        $stmt = $this->conexao->prepare($sql);

        $stmt->bindValue(
            ':idPessoa',
            $idPessoa,
            PDO::PARAM_INT
        );

        $stmt->bindValue(
            ':debito',
            $valor
        );

        $stmt->bindValue(
            ':dataOperacao',
            date('Y-m-d')
        );

        $stmt->bindValue(
            ':observacao',
            $observacao
        );

        return $stmt->execute();
    }

    // =====================================================
    // TRANSFERÊNCIA
    // =====================================================

    public function transferir(
        int $origem,
        int $destino,
        float $valor
    ): bool {

        if ($origem === $destino) {
            return false;
        }

        $saldo = $this->saldo($origem);

        if ($valor > $saldo) {
            return false;
        }

        try {

            $this->conexao->beginTransaction();

            // SAÍDA
            $sql = "
                INSERT INTO movimentacao
                (
                    idPessoa,
                    Credito,
                    Debito,
                    DataOperacao,
                    Observacao
                )
                VALUES
                (
                    :idPessoa,
                    NULL,
                    :valor,
                    :data,
                    :observacao
                )
            ";

            $stmt = $this->conexao->prepare($sql);

            $stmt->bindValue(
                ':idPessoa',
                $origem,
                PDO::PARAM_INT
            );

            $stmt->bindValue(
                ':valor',
                $valor
            );

            $stmt->bindValue(
                ':data',
                date('Y-m-d')
            );

            $stmt->bindValue(
                ':observacao',
                'Transferência enviada'
            );

            $stmt->execute();

            // ENTRADA
            $stmt = $this->conexao->prepare($sql);

            $stmt->bindValue(
                ':idPessoa',
                $destino,
                PDO::PARAM_INT
            );

            $stmt->bindValue(
                ':valor',
                $valor
            );

            $stmt->bindValue(
                ':data',
                date('Y-m-d')
            );

            $stmt->bindValue(
                ':observacao',
                'Transferência recebida'
            );

            $stmt->execute();

            $this->conexao->commit();

            return true;

        } catch (\Exception $e) {

            $this->conexao->rollBack();

            return false;
        }
    }

    // =====================================================
    // LISTAR TUDO
    // =====================================================

    public function listar(): array
    {
        $sql = "
            SELECT
                m.id,
                m.idPessoa,
                m.Credito,
                m.Debito,
                m.DataOperacao,
                m.Observacao,
                p.nome AS pessoa_nome

            FROM movimentacao m

            INNER JOIN pessoas p
                ON p.id = m.idPessoa

            ORDER BY
                m.DataOperacao DESC,
                m.id DESC
        ";

        $stmt = $this->conexao->prepare($sql);

        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // =====================================================
    // EXTRATO DE UMA PESSOA
    // =====================================================

    public function extrato(int $idPessoa): array
    {
        $sql = "
            SELECT
                m.*,
                p.nome AS pessoa_nome

            FROM movimentacao m

            INNER JOIN pessoas p
                ON p.id = m.idPessoa

            WHERE m.idPessoa = :idPessoa

            ORDER BY
                m.DataOperacao DESC,
                m.id DESC
        ";

        $stmt = $this->conexao->prepare($sql);

        $stmt->bindValue(
            ':idPessoa',
            $idPessoa,
            PDO::PARAM_INT
        );

        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // =====================================================
    // RESUMO POR PESSOA
    // =====================================================

    public function resumoPorPessoa(): array
    {
        $sql = "
            SELECT
                p.id,
                p.nome,

                COALESCE(SUM(m.Credito), 0) AS total_credito,

                COALESCE(SUM(m.Debito), 0) AS total_debito,

                COALESCE(SUM(m.Credito), 0)
                -
                COALESCE(SUM(m.Debito), 0) AS saldo

            FROM pessoas p

            LEFT JOIN movimentacao m
                ON m.idPessoa = p.id

            GROUP BY
                p.id,
                p.nome

            ORDER BY p.nome
        ";

        $stmt = $this->conexao->prepare($sql);

        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // =====================================================
    // RESUMO GERAL
    // =====================================================

    public function resumoGeral(): array
    {
        $sql = "
            SELECT

                COALESCE(SUM(Credito), 0) AS total_credito,

                COALESCE(SUM(Debito), 0) AS total_debito,

                COALESCE(SUM(Credito), 0)
                -
                COALESCE(SUM(Debito), 0) AS saldo

            FROM movimentacao
        ";

        $stmt = $this->conexao->prepare($sql);

        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}