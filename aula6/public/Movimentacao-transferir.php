<?php

require "../vendor/autoload.php";

use App\DAO\PessoaDAO;
use App\DAO\MovimentacaoDAO;

$pessoaDAO = new PessoaDAO();
$movimentacaoDAO = new MovimentacaoDAO();

$idPessoa = (int) ($_POST['idPessoa'] ?? 0);

$pessoaOrigem = $pessoaDAO->buscarPorId($idPessoa);

if (!$pessoaOrigem) {
    die("Pessoa não encontrada.");
}

$saldo = $movimentacaoDAO->saldo($idPessoa);

$pessoas = $pessoaDAO->listar();

ob_start();

?>

<h2 class="mb-4">Transferir</h2>

<div class="alert alert-info">

    <strong>Origem:</strong>

    <?= htmlspecialchars($pessoaOrigem['nome']) ?>

    <br>

    <strong>Saldo:</strong>

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
        value="transferir"
    >

    <input
        type="hidden"
        name="origem"
        value="<?= $idPessoa ?>"
    >


    <div class="mb-3">

        <label
            for="destino"
            class="form-label"
        >
            Pessoa de destino
        </label>

        <select
            name="destino"
            id="destino"
            class="form-select"
            required
        >

            <option value="">
                Selecione a pessoa
            </option>

            <?php foreach ($pessoas as $pessoa): ?>

                <?php if ($pessoa['id'] != $idPessoa): ?>

                    <option value="<?= $pessoa['id'] ?>">

                        <?= htmlspecialchars(
                            $pessoa['nome']
                        ) ?>

                    </option>

                <?php endif; ?>

            <?php endforeach; ?>

        </select>

    </div>


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
            max="<?= $saldo ?>"
            required
        >

    </div>


    <button
        type="submit"
        class="btn btn-primary"
    >
        Transferir
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