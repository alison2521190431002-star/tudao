<?php

require "../vendor/autoload.php";

use App\DAO\PessoaDAO;
use App\DAO\MovimentacaoDAO;

$pessoaDAO = new PessoaDAO();
$movimentacaoDAO = new MovimentacaoDAO();

$nome = $_POST['nome'] ?? $_GET['nome'] ?? '';

$pagina = isset($_GET['pagina'])
    ? (int) $_GET['pagina']
    : 1;

if ($pagina < 1) {
    $pagina = 1;
}

$porPagina = 5;

$pessoas = $pessoaDAO->pesquisarPaginado(
    $nome,
    $pagina,
    $porPagina
);

$total = $pessoaDAO->contarPesquisa($nome);

$totalPaginas = ceil($total / $porPagina);

ob_start();

?>

<h2 class="mb-4">Pesquisar Pessoas</h2>

<form method="post" class="mb-4">

    <div class="input-group">

        <input
            type="text"
            name="nome"
            class="form-control"
            placeholder="Digite o nome"
            value="<?= htmlspecialchars($nome) ?>"
            autofocus
        >

        <button
            type="submit"
            class="btn btn-success"
        >
            Pesquisar
        </button>

    </div>

</form>


<?php if (count($pessoas) > 0): ?>

    <div class="table-responsive">

        <table class="table table-striped table-hover table-bordered">

            <thead class="table-dark">

                <tr>
                    <th>ID</th>
                    <th>Nome</th>
                    <th>Saldo</th>
                    <th>Ações</th>
                </tr>

            </thead>

            <tbody>

                <?php foreach ($pessoas as $pessoa): ?>

                    <?php

                    $saldo = $movimentacaoDAO->saldo(
                        (int) $pessoa['id']
                    );

                    ?>

                    <tr>

                        <td>
                            <?= $pessoa['id'] ?>
                        </td>

                        <td>
                            <?= htmlspecialchars($pessoa['nome']) ?>
                        </td>

                        <td>

                            R$
                            <?= number_format(
                                $saldo,
                                2,
                                ',',
                                '.'
                            ) ?>

                        </td>

                        <td>

                            <form
                                method="post"
                                action="Movimentacao-depositar.php"
                                style="display:inline"
                            >

                                <input
                                    type="hidden"
                                    name="idPessoa"
                                    value="<?= $pessoa['id'] ?>"
                                >

                                <button
                                    type="submit"
                                    class="btn btn-success btn-sm"
                                >
                                    Depositar
                                </button>

                            </form>


                            <form
                                method="post"
                                action="Movimentacao-sacar.php"
                                style="display:inline"
                            >

                                <input
                                    type="hidden"
                                    name="idPessoa"
                                    value="<?= $pessoa['id'] ?>"
                                >

                                <button
                                    type="submit"
                                    class="btn btn-danger btn-sm"
                                >
                                    Sacar
                                </button>

                            </form>


                            <form
                                method="post"
                                action="Movimentacao-transferir.php"
                                style="display:inline"
                            >

                                <input
                                    type="hidden"
                                    name="idPessoa"
                                    value="<?= $pessoa['id'] ?>"
                                >

                                <button
                                    type="submit"
                                    class="btn btn-primary btn-sm"
                                >
                                    Transferir
                                </button>

                            </form>

                        </td>

                    </tr>

                <?php endforeach; ?>

            </tbody>

        </table>

    </div>


    <?php if ($totalPaginas > 1): ?>

        <nav>

            <ul class="pagination">

                <?php for (
                    $i = 1;
                    $i <= $totalPaginas;
                    $i++
                ): ?>

                    <li
                        class="page-item
                        <?= $i == $pagina ? 'active' : '' ?>"
                    >

                        <a
                            class="page-link"
                            href="Movimentacao-pesquisar.php?pagina=<?= $i ?>&nome=<?= urlencode($nome) ?>"
                        >
                            <?= $i ?>
                        </a>

                    </li>

                <?php endfor; ?>

            </ul>

        </nav>

    <?php endif; ?>


<?php else: ?>

    <div class="alert alert-warning">
        Nenhuma pessoa encontrada.
    </div>

<?php endif; ?>


<?php

$content = ob_get_clean();

require "Layout.php";

require "Footer.php";

?>