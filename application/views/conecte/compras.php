<?php

if (!$results) { ?>
    <div class="widget-box">
        <div class="widget-title">
            <span class="icon">
                <i class="fas fa-tags"></i>
            </span>
            <h5>Compras</h5>

        </div>

        <div class="widget-content nopadding tab-content">


            <table id="tabela" class="table table-bordered ">
                <thead>
                    <tr style="background-color: #2D335B">
                        <th>#</th>
                        <th>Data da Compra</th>
                        <th>Responsável</th>
                        <th>Faturado</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>

                    <tr>
                        <td colspan="6">Nenhuma compra cadastrada</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
<?php
} else { ?>


    <div class="widget-box">
        <div class="widget-title">
            <span class="icon">
                <i class="fas fa-shopping-cart"></i>
            </span>
            <h5>Compras</h5>

        </div>

        <div class="widget-content nopadding tab-content">


            <table id="tabela" class="table table-bordered ">
                <thead>
                    <tr style="background-color: #2D335B">
                        <th>#</th>
                        <th>Data da Compra</th>
                        <th>Responsável</th>
                        <th>Faturado</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($results as $r) {
    $dataVenda = date(('d/m/Y'), strtotime($r->dataVenda));
    if ($r->faturado == 1) {
        $faturado = 'Sim';
    } else {
        $faturado = 'Não';
    }
    echo '<tr>';
    echo '<td>' . $r->idVendas . '</td>';
    echo '<td>' . $dataVenda . '</td>';
    echo '<td>' . $r->nome . '</td>';
    echo '<td>' . $faturado . '</td>';

    echo '<td><a href="' . base_url() . 'index.php/mine/visualizarCompra/' . $r->idVendas . '" class="btn tip-top" title="Ver mais detalhes"><i class="fas fa-eye"></i></a>
                      <a href="' . base_url() . 'index.php/mine/imprimirCompra/' . $r->idVendas . '" target="_blank" class="btn btn-inverse tip-top" title="Imprimir"><i class="fas fa-print"></i></a>

                  </td>';
    echo '</tr>';
} ?>
                </tbody>
            </table>
        </div>
    </div>

<?php echo $this->pagination->create_links();
} ?>
