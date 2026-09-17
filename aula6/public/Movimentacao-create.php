<?php

require "../vendor/autoload.php";

ob_start();

?>

<h2 class="mb-4">Cadastrar Movimentação</h2>

<form action="movimentacao-cadastrar.php" method="POST">

    <!-- PESSOA -->
    <div class="mb-3">

        <label for="nomePessoa" class="form-label">
            Pessoa
        </label>

        <input
            type="text"
            class="form-control"
            id="nomePessoa"
            name="nomePessoa"
            placeholder="Digite seu nome"
            required
        >

    </div>

    <!-- DESCRIÇÃO -->
    <div class="mb-3">

        <label for="observacao" class="form-label">
            Descrição
        </label>

        <input
            type="text"
            class="form-control"
            id="observacao"
            name="observacao"
            maxlength="255"
            placeholder="Digite a descrição"
            required
        >

    </div>

    <!-- TIPO -->
    <div class="mb-3">

        <label for="tipo" class="form-label">
            Tipo
        </label>

        <select
            class="form-select"
            id="tipo"
            name="tipo"
            required
        >

            <option value="">
                Selecione o tipo
            </option>

            <option value="CREDITO">
                Entrada (Crédito)
            </option>

            <option value="DEBITO">
                Saída (Débito)
            </option>

        </select>

    </div>

    <!-- VALOR -->
    <div class="mb-3">

        <label for="valor" class="form-label">
            Valor
        </label>

        <input
            type="number"
            class="form-control"
            id="valor"
            name="valor"
            step="0.01"
            min="0.01"
            placeholder="Digite o valor"
            required
        >

    </div>

    <!-- DATA -->
    <div class="mb-3">

        <label for="dataOperacao" class="form-label">
            Data da Operação
        </label>

        <input
            type="date"
            class="form-control"
            id="dataOperacao"
            name="dataOperacao"
            required
        >

    </div>

    <button type="submit" class="btn btn-primary">
        Cadastrar
    </button>

    <a href="index.php" class="btn btn-secondary">
        Voltar
    </a>

</form>

<?php

$content = ob_get_clean();

require "layout.php";

require "footer.php";

?>
