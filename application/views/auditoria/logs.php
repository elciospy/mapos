<a href="#modal-excluir" role="button" data-toggle="modal" class="btn btn-danger tip-top" title="Excluir Logs"><i class="fas fa-trash-alt"></i> Remover Logs - 30 dias ou mais</a>

<div class="widget-box">
    <div class="widget-title">
        <span class="icon">
            <i class="fas fa-clock"></i>
        </span>
        <h5>Logs</h5>
    </div>
    <div class="widget-content nopadding tab-content">
        <table id="tabela" class="table table-bordered ">
            <thead>
                <tr style="background-color: #2D335B">
                    <th>Usuário</th>
                    <th>Data</th>
                    <th>Hora</th>
                    <th>IP</th>
                    <th>Tarefa</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($results as $r) {
    echo '<tr>';
    echo '<td>' . $r->usuario . '</td>';
    echo '<td>' . date('d/m/Y', strtotime($r->data)) . '</td>';
    echo '<td>' . $r->hora . '</td>';
    echo '<td>' . $r->ip . '</td>';
    echo '<td>' . $r->tarefa . '</td>';
    echo '</tr>';
} ?>
                <?php if (!$results) { ?>
                    <tr>
                        <td colspan="5">Nenhum registro encontrado.</td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
</div>
<?php echo $this->pagination->create_links(); ?>

<!-- Modal -->
<div id="modal-excluir" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <form action="<?php echo site_url('auditoria/clean') ?>" method="post">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            <h5>Limpeza de Logs</h5>
        </div>
        <div class="modal-body">
            <h5 style="text-align: center">Deseja realmente remover os logs mais antigos?</h5>
        </div>
        <div class="modal-footer">
            <button class="btn" data-dismiss="modal" aria-hidden="true">Cancelar</button>
            <button class="btn btn-danger">Excluir</button>
        </div>
    </form>
</div>
