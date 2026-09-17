<?php

require "../vendor/autoload.php";

use App\DAO\MovimentacaoDAO;

$dao = new MovimentacaoDAO();

$movimentacoes = $dao->listar();

$resumos = $dao->resumoPorPessoa();

$resumoGeral = $dao->resumoGeral();

ob_start();

?>

<h2 class="mb-4">Extrato de Movimentações</h2>


<!-- RESUMO GERAL -->

<div class="row mb-4">

    <div class="col-md-4">

        <div class="card text-bg-success">

            <div class="card-body">

                <h5>Total de Entradas</h5>

                <h3>
                    R$
                    <?= number_format(
                        $resumoGeral['total_credito'],
                        2,
                        ',',
                        '.'
                    ) ?>
                </h3>

            </div>

        </div>

    </div>


    <div class="col-md-4">

        <div class="card text-bg-danger">

            <div class="card-body">

                <h5>Total de Saídas</h5>

                <h3>
                    R$
                    <?= number_format(
                        $resumoGeral['total_debito'],
                        2,
                        ',',
                        '.'
                    ) ?>
                </h3>

            </div>

        </div>

    </div>


    <div class="col-md-4">

        <div class="card text-bg-primary">

            <div class="card-body">

                <h5>Saldo Geral</h5>

                <h3>
                    R$
                    <?= number_format(
                        $resumoGeral['saldo'],
                        2,
                        ',',
                        '.'
                    ) ?>
                </h3>

            </div>

        </div>

    </div>

</div>


<!-- RESUMO POR PESSOA -->

<h4 class="mb-3">
    Saldo por Pessoa
</h4>


<div class="table-responsive mb-5">

    <table class="table table-striped table-hover table-bordered">

        <thead class="table-dark">

            <tr>
                <th>ID</th>
                <th>Pessoa</th>
                <th>Entradas</th>
                <th>Saídas</th>
                <th>Saldo</th>
            </tr>

        </thead>

        <tbody>

            <?php foreach ($resumos as $resumo): ?>

                <tr>

                    <td>
                        <?= $resumo['id'] ?>
                    </td>

                    <td>
                        <?= htmlspecialchars(
                            $resumo['nome']
                        ) ?>
                    </td>

                    <td>
                        R$
                        <?= number_format(
                            $resumo['total_credito'],
                            2,
                            ',',
                            '.'
                        ) ?>
                    </td>

                    <td>
                        R$
                        <?= number_format(
                            $resumo['total_debito'],
                            2,
                            ',',
                            '.'
                        ) ?>
                    </td>

                    <td>
                        <strong>
                            R$
                            <?= number_format(
                                $resumo['saldo'],
                                2,
                                ',',
                                '.'
                            ) ?>
                        </strong>
                    </td>

                </tr>

            <?php endforeach; ?>

        </tbody>

    </table>

</div>


<!-- MOVIMENTAÇÕES -->

<h4 class="mb-3">
    Todas as Movimentações
</h4>


<?php if (count($movimentacoes) > 0): ?>

    <div class="table-responsive">

        <table class="table table-striped table-hover table-bordered">

            <thead class="table-dark">

                <tr>

                    <th>ID</th>
                    <th>Pessoa</th>
                    <th>Descrição</th>
                    <th>Tipo</th>
                    <th>Valor</th>
                    <th>Data</th>

                </tr>

            </thead>


            <tbody>

                <?php foreach ($movimentacoes as $movimentacao): ?>

                    <?php

                    if (
                        $movimentacao['Credito'] !== null
                        &&
                        $movimentacao['Credito'] > 0
                    ) {

                        $tipo = 'Entrada';

                        $valor = $movimentacao['Credito'];

                    } else {

                        $tipo = 'Saída';

                        $valor = $movimentacao['Debito'];
                    }

                    ?>


                    <tr>

                        <td>
                            <?= $movimentacao['id'] ?>
                        </td>

                        <td>
                            <?= htmlspecialchars(
                                $movimentacao['pessoa_nome']
                            ) ?>
                        </td>

                        <td>
                            <?= htmlspecialchars(
                                $movimentacao['Observacao']
                            ) ?>
                        </td>

                        <td>

                            <?php if ($tipo === 'Entrada'): ?>

                                <span class="badge bg-success">
                                    Entrada
                                </span>

                            <?php else: ?>

                                <span class="badge bg-danger">
                                    Saída
                                </span>

                            <?php endif; ?>

                        </td>

                        <td>

                            R$
                            <?= number_format(
                                $valor,
                                2,
                                ',',
                                '.'
                            ) ?>

                        </td>

                        <td>

                            <?= date(
                                'd/m/Y',
                                strtotime(
                                    $movimentacao['DataOperacao']
                                )
                            ) ?>

                        </td>

                    </tr>

                <?php endforeach; ?>

            </tbody>

        </table>

    </div>

<?php else: ?>

    <div class="alert alert-warning">
        Nenhuma movimentação cadastrada.
    </div>

<?php endif; ?>


<a
    href="Movimentacao-pesquisar.php"
    class="btn btn-primary mt-3"
>
    Pesquisar Pessoas
</a>


<?php

$content = ob_get_clean();

require "Layout.php";

require "Footer.php";

?>