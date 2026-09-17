<?php

require "../vendor/autoload.php";

use App\DAO\PessoaDAO;

$dao = new PessoaDAO();

/*
    PESQUISA
*/
$busca = $_POST['nome'] ?? '';

if ($busca !== '') {
    $pessoas = $dao->pesquisar($busca);
} else {
    $pessoas = $dao->listar();
}


/*
    PAGINAÇÃO
*/
$porPagina = 5;

$totalPessoas = count($pessoas);

$totalPaginas = ceil($totalPessoas / $porPagina);

$pagina = isset($_GET['pagina'])
    ? (int) $_GET['pagina']
    : 1;

if ($pagina < 1) {
    $pagina = 1;
}

if ($pagina > $totalPaginas && $totalPaginas > 0) {
    $pagina = $totalPaginas;
}

$inicio = ($pagina - 1) * $porPagina;

$pessoasPagina = array_slice(
    $pessoas,
    $inicio,
    $porPagina
);


ob_start();

?>

<div class="container py-4">

    <!-- TÍTULO -->

    <div class="mb-4">

        <h2 class="fw-bold">
            Pessoas
        </h2>

        <p class="text-muted">
            Pesquise uma pessoa para realizar uma movimentação.
        </p>

    </div>


    <!-- PESQUISA -->

    <div class="card border-0 shadow-lg rounded-4">

        <div class="card-body p-4">

            <form method="post" class="mb-4">

                <div class="input-group">

                    <input
                        type="text"
                        name="nome"
                        class="form-control"
                        placeholder="Digite o nome"
                        value="<?= htmlspecialchars($busca) ?>"
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


            <!-- TABELA -->

            <?php if (count($pessoasPagina) > 0): ?>

                <div class="table-responsive">

                    <table class="table table-hover align-middle">

                        <thead class="table-primary">

                            <tr>

                                <th>ID</th>

                                <th>Nome</th>

                                <th>CPF</th>

                                <th>Telefone</th>

                                <th>Endereço</th>

                                <th>Ações</th>

                            </tr>

                        </thead>


                        <tbody>

                            <?php foreach ($pessoasPagina as $pessoa): ?>

                                <tr>

                                    <td>
                                        <?= htmlspecialchars($pessoa['id']) ?>
                                    </td>


                                    <td>
                                        <?= htmlspecialchars($pessoa['nome']) ?>
                                    </td>


                                    <td>
                                        <?= htmlspecialchars($pessoa['cpf'] ?? '') ?>
                                    </td>


                                    <td>
                                        <?= htmlspecialchars($pessoa['telefone'] ?? '') ?>
                                    </td>


                                    <td>
                                        <?= htmlspecialchars($pessoa['endereco'] ?? '') ?>
                                    </td>


                                    <td>

                                        <div class="d-flex gap-1">


                                            <!-- DEPOSITAR -->

                                            <form
                                                method="post"
                                                action="Movimentacao-depositar.php"
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


                                            <!-- SACAR -->

                                            <form
                                                method="post"
                                                action="Movimentacao-sacar.php"
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


                                            <!-- TRANSFERIR -->

                                            <form
                                                method="post"
                                                action="Movimentacao-transferir.php"
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


                                        </div>

                                    </td>

                                </tr>

                            <?php endforeach; ?>

                        </tbody>

                    </table>

                </div>


                <!-- PAGINAÇÃO -->

                <?php if ($totalPaginas > 1): ?>

                    <nav class="mt-4">

                        <ul class="pagination justify-content-center">


                            <!-- ANTERIOR -->

                            <li
                                class="page-item
                                <?= ($pagina <= 1) ? 'disabled' : '' ?>"
                            >

                                <a
                                    class="page-link"
                                    href="?pagina=<?= $pagina - 1 ?>"
                                >
                                    Anterior
                                </a>

                            </li>


                            <!-- NÚMEROS -->

                            <?php for (
                                $i = 1;
                                $i <= $totalPaginas;
                                $i++
                            ): ?>

                                <li
                                    class="page-item
                                    <?= ($i == $pagina) ? 'active' : '' ?>"
                                >

                                    <a
                                        class="page-link"
                                        href="?pagina=<?= $i ?>"
                                    >
                                        <?= $i ?>
                                    </a>

                                </li>

                            <?php endfor; ?>


                            <!-- PRÓXIMA -->

                            <li
                                class="page-item
                                <?= ($pagina >= $totalPaginas)
                                    ? 'disabled'
                                    : '' ?>"
                            >

                                <a
                                    class="page-link"
                                    href="?pagina=<?= $pagina + 1 ?>"
                                >
                                    Próxima
                                </a>

                            </li>


                        </ul>

                    </nav>

                    
                <?php endif; ?>


            <?php else: ?>

                <div class="alert alert-warning">

                    Nenhuma pessoa encontrada.

                </div>

            <?php endif; ?>


        </div>

    </div>

</div>


<?php

$content = ob_get_clean();

require "Layout.php";

require "Footer.php";

?>