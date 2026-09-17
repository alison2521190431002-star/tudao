<?php

require "../vendor/autoload.php";

use App\DAO\PessoaDAO;
use App\DAO\MovimentacaoDAO;

$pessoaDAO = new PessoaDAO();
$movimentacaoDAO = new MovimentacaoDAO();

$idPessoa = (int) ($_POST['idPessoa'] ?? $_GET['idPessoa'] ?? 0);

$pessoa = $pessoaDAO->buscarPorId($idPessoa);

if (!$pessoa) {
    die("Pessoa não encontrada.");
}

$saldo = $movimentacaoDAO->saldo($idPessoa);

ob_start();

?>

<h2 class="mb-4">Depositar</h2>

<div class="alert alert-info">

    <strong>Pessoa:</strong>

    <?= htmlspecialchars($pessoa['nome']) ?>

    <br>

    <strong>Saldo atual:</strong>

    R$
    <?= number_format(
        $saldo,
        2,
        ',',
        '.'
    ) ?>

</div>


<form
    method="post"
    action="Movimentacao-processar.php"
>

    <input
        type="hidden"
        name="acao"
        value="depositar"
    >

    <input
        type="hidden"
        name="idPessoa"
        value="<?= $idPessoa ?>"
    >


    <div class="mb-3">

        <label
            for="valor"
            class="form-label"
        >
            Valor
        </label>

        <input
            type="number"
            name="valor"
            id="valor"
            class="form-control"
            step="0.01"
            min="0.01"
            required
        >

    </div>


    <div class="mb-3">

        <label
            for="observacao"
            class="form-label"
        >
            Descrição
        </label>

        <input
            type="text"
            name="observacao"
            id="observacao"
            class="form-control"
            placeholder="Digite a descrição"
            required
        >

    </div>


    <button
        type="submit"
        class="btn btn-success"
    >
        Depositar
    </button>

    <a
        href="Movimentacao-pesquisar.php"
        class="btn btn-secondary"
    >
        Voltar
    </a>

</form>


<?php

$content = ob_get_clean();

require "Layout.php";

require "Footer.php";

?>