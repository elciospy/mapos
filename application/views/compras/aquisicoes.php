<?php if ($this->permission->checkPermission($this->session->userdata('permissao'), 'aAquisicao')) { ?>
    <a href="<?php echo base_url(); ?>index.php/aquisicoes/adicionar" class="btn btn-success"><i class="fas fa-plus"></i> Adicionar Aquisição</a>
    

<?php } ?>

<div class="widget-box">
    <div class="widget-title">
        <span class="icon">
            <i class="fas fa-shopping-bag"></i>
        </span>
        <h5>Aquisições</h5>
    </div>
    <div class="widget-content nopadding tab-content">
        <table id="tabela" class="table table-bordered ">
            <thead>
            <tr style="background-color: #2D335B">
                <th>Cod. Produto</th>
                <th>Tipo</th>
                <th>Marca</th>
                <th>Modelo</th>
                <th>Data Aquisição</th>
                <th>Valor</th>
                <th>Ações</th>
            </tr>
            </thead>
            <tbody>
            <?php

            if (!$results) {
                echo '<tr>
                            <td colspan="5">Nenhum Produto Cadastrado</td>
                            </tr>';
            }
            foreach ($results as $r) {

                $dataAquisicao = date(('d/m/Y'), strtotime($r->dataAquisicao));

                echo '<tr>';
                echo '<td>' . $r->idAquisicao . '</td>';
                echo '<td>' . $r->tipoAquisicao . '</td>';
                echo '<td>' . $r->marca . '</td>';
                echo '<td>' . $r->modelo . '</td>';
                echo '<td>' . $dataAquisicao . '</td>';
                echo '<td>' . number_format($r->precoCompra, 2, ',', '.') . '</td>';
                echo '<td>';
                if ($this->permission->checkPermission($this->session->userdata('permissao'), 'vAquisicao')) {
                    echo '<a style="margin-right: 1%" href="' . base_url() . 'index.php/aquisicoes/visualizar/' . $r->idAquisicao . '" class="btn tip-top" title="Visualizar Aquisição"><i class="fas fa-eye"></i></a>  ';
                }
                if ($this->permission->checkPermission($this->session->userdata('permissao'), 'eAquisicao')) {
                    echo '<a style="margin-right: 1%" href="' . base_url() . 'index.php/aquisicoes/editar/' . $r->idAquisicao . '" class="btn btn-info tip-top" title="Editar Aquisição"><i class="fas fa-edit"></i></a>';
                }
                if ($this->permission->checkPermission($this->session->userdata('permissao'), 'dAquisicao')) {
                    echo '<a style="margin-right: 1%" href="#modal-excluir" role="button" data-toggle="modal" idAquisicao="' . $r->idAquisicao . '" class="btn btn-danger tip-top delete" title="Excluir Aquisição"><i class="fas fa-trash-alt"></i></a>';
                }
                /*
                if ($this->permission->checkPermission($this->session->userdata('permissao'), 'eAquisicao')) {
                    echo '<a href="#atualizar-estoque" role="button" data-toggle="modal" produto="' . $r->idAquisicao . '" estoque="' . $r->estoque . '" class="btn btn-primary tip-top" title="Atualizar Estoque"><i class="fas fa-plus-square"></i></a>';
                }*/
                echo '</td>';
                echo '</tr>';
            } ?>
            </tbody>
        </table>
    </div>
</div>
<?php echo $this->pagination->create_links(); ?>

<!-- Modal -->
<div id="modal-excluir" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <form action="<?php echo base_url() ?>index.php/aquisicoes/excluir" method="post">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            <h5 id="myModalLabel"><i class="fas fa-trash-alt"></i> Excluir Produto</h5>
        </div>
        <div class="modal-body">
            <input type="hidden" id="idAquisicao" value="" name="idAquisicao"/>
            <h5 style="text-align: center">Deseja realmente excluir esta aquisição?</h5>
        </div>
        <div class="modal-footer">
            <button class="btn" data-dismiss="modal" aria-hidden="true">Cancelar</button>
            <button class="btn btn-danger">Excluir</button>
        </div>
    </form>
</div>

<!-- Modal Estoque -->
<div id="atualizar-estoque" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <form action="<?php echo base_url() ?>index.php/produtos/atualizar_estoque" method="post" id="formEstoque">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            <h5 id="myModalLabel"><i class="fas fa-plus-square"></i> Atualizar Estoque</h5>
        </div>
        <div class="modal-body">
            <div class="control-group">
                <label for="estoqueAtual" class="control-label">Estoque Atual</label>
                <div class="controls">
                    <input id="estoqueAtual" type="text" name="estoqueAtual" value="" readonly />
                </div>
            </div>

            <div class="control-group">
                <label for="estoque" class="control-label">Adicionar Produtos<span class="required">*</span></label>
                <div class="controls">
                    <input type="hidden" id="idProduto" class="idProduto" name="id" value=""/>
                    <input id="estoque" type="text" name="estoque" value=""/>
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <button class="btn" data-dismiss="modal" aria-hidden="true">Cancelar</button>
            <button class="btn btn-primary">Atualizar</button>
        </div>
    </form>
</div>

<!-- Modal Etiquetas -->
<div id="modal-etiquetas" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <form action="<?php echo base_url() ?>index.php/relatorios/produtosEtiquetas" method="get">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            <h5 id="myModalLabel">Gerar etiquetas com Código de Barras</h5>
        </div>
        <div class="modal-body">
            <div class="span12 alert alert-info" style="margin-left: 0"> Escolha o intervalo de produtos para gerar as etiquetas.</div>

            <div class="span12" style="margin-left: 0;">
                <div class="span6" style="margin-left: 0;">
                    <label for="valor">De</label>
                    <input class="span9" style="margin-left: 0" type="text" id="de_id" name="de_id" placeholder="ID do primeiro produto" value=""/>
                </div>


                <div class="span6">
                    <label for="valor">Até</label>
                    <input class="span9" type="text" id="ate_id" name="ate_id" placeholder="ID do último produto" value=""/>
                </div>

                <div class="span4">
                    <label for="valor">Qtd. do Estoque</label>
                    <input class="span12" type="checkbox" name="qtdEtiqueta" value="true"/>
                </div>

                <div class="span6">
                    <label class="span12" for="valor">Formato Etiqueta</label>
                    <select name="etiquetaCode">
                        <option value="EAN13">EAN-13</option>
                        <option value="UPCA">UPCA</option>
                        <option value="C93">CODE 93</option>
                        <option value="C128A">CODE 128</option>
                        <option value="CODABAR">CODABAR</option>
                        <option value="QR">QR-CODE</option>
                    </select>
                </div>

            </div>
        </div>
        <div class="modal-footer">
            <button class="btn" data-dismiss="modal" aria-hidden="true">Cancelar</button>
            <button class="btn btn-success">Gerar</button>
        </div>
    </form>
</div>

<script src="<?php echo base_url() ?>assets/js/jquery.validate.js"></script>
<!-- Modal Etiquetas e Estoque-->
<script type="text/javascript">
    $(document).ready(function () {
        $(document).on('click', 'a.delete', function (event) {
            var idAquisicao = $(this).attr('idAquisicao');
            $('#idAquisicao').val(idAquisicao);
        });

        $('#formEstoque').validate({
            rules: {
                estoque: {
                    required: true,
                    number: true
                }
            },
            messages: {
                estoque: {
                    required: 'Campo Requerido.',
                    number: 'Informe um número válido.'
                }
            },
            errorClass: "help-inline",
            errorElement: "span",
            highlight: function (element, errorClass, validClass) {
                $(element).parents('.control-group').addClass('error');
            },
            unhighlight: function (element, errorClass, validClass) {
                $(element).parents('.control-group').removeClass('error');
                $(element).parents('.control-group').addClass('success');
            }
        });
    });
</script>
