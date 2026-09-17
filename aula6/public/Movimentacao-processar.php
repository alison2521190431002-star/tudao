<?php

require "../vendor/autoload.php";

use App\DAO\MovimentacaoDAO;

$dao = new MovimentacaoDAO();

$acao = $_POST['acao'] ?? '';


// =====================================================
// DEPOSITAR
// =====================================================

if ($acao === 'depositar') {

    $idPessoa = (int) ($_POST['idPessoa'] ?? 0);

    $valor = (float) ($_POST['valor'] ?? 0);

    $observacao = $_POST['observacao'] ?? '';


    if ($idPessoa <= 0 || $valor <= 0) {

        echo "<script>
            alert('Dados inválidos!');
            history.back();
        </script>";

        exit;
    }


    $resultado = $dao->depositar(
        $idPessoa,
        $valor,
        $observacao
    );


    if ($resultado) {

        echo "<script>
            alert('Depósito realizado com sucesso!');
            window.location='Movimentacao-pesquisar.php';
        </script>";

    } else {

        echo "<script>
            alert('Erro ao realizar depósito!');
            history.back();
        </script>";
    }

    exit;
}


// =====================================================
// SACAR
// =====================================================

if ($acao === 'sacar') {

    $idPessoa = (int) ($_POST['idPessoa'] ?? 0);

    $valor = (float) ($_POST['valor'] ?? 0);

    $observacao = $_POST['observacao'] ?? '';


    if ($idPessoa <= 0 || $valor <= 0) {

        echo "<script>
            alert('Dados inválidos!');
            history.back();
        </script>";

        exit;
    }


    $resultado = $dao->sacar(
        $idPessoa,
        $valor,
        $observacao
    );


    if ($resultado) {

        echo "<script>
            alert('Saque realizado com sucesso!');
            window.location='Movimentacao-pesquisar.php';
        </script>";

    } else {

        echo "<script>
            alert('Saldo insuficiente ou erro no saque!');
            history.back();
        </script>";
    }

    exit;
}


// =====================================================
// TRANSFERIR
// =====================================================

if ($acao === 'transferir') {

    $origem = (int) ($_POST['origem'] ?? 0);

    $destino = (int) ($_POST['destino'] ?? 0);

    $valor = (float) ($_POST['valor'] ?? 0);


    if (
        $origem <= 0 ||
        $destino <= 0 ||
        $valor <= 0
    ) {

        echo "<script>
            alert('Dados inválidos!');
            history.back();
        </script>";

        exit;
    }


    $resultado = $dao->transferir(
        $origem,
        $destino,
        $valor
    );


    if ($resultado) {

        echo "<script>
            alert('Transferência realizada com sucesso!');
            window.location='Movimentacao-pesquisar.php';
        </script>";

    } else {

        echo "<script>
            alert('Saldo insuficiente ou transferência inválida!');
            history.back();
        </script>";
    }

    exit;
}


echo "<script>
    alert('Operação inválida!');
    window.location='Movimentacao-pesquisar.php';
</script>";