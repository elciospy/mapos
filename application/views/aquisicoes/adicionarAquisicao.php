<link rel="stylesheet" href="<?php echo base_url(); ?>assets/js/jquery-ui/css/smoothness/jquery-ui-1.9.2.custom.css" />
<script type="text/javascript" src="<?php echo base_url() ?>assets/js/jquery-ui/js/jquery-ui-1.9.2.custom.js"></script>

<style>
    .ui-datepicker {
        z-index: 9999 !important;
    }
    /* Hiding the checkbox, but allowing it to be focused */
    .badgebox {
        opacity: 0;
    }

    .badgebox+.badge {
        /* Move the check mark away when unchecked */
        text-indent: -999999px;
        /* Makes the badge's width stay the same checked and unchecked */
        width: 27px;
    }

    .badgebox:focus+.badge {
        /* Set something to make the badge looks focused */
        /* This really depends on the application, in my case it was: */

        /* Adding a light border */
        box-shadow: inset 0px 0px 5px;
        /* Taking the difference out of the padding */
    }

    .badgebox:checked+.badge {
        /* Move the check mark back when checked */
        text-indent: 0;
    }
</style>
<div class="row-fluid" style="margin-top:0">
    <div class="span12">
        <div class="widget-box">
            <div class="widget-title">
                <span class="icon">
                    <i class="fas fa-shopping-bag"></i>
                </span>
                <h5>Cadastro de Aquisição</h5>
            </div>
            <div class="widget-content nopadding tab-content">
                <?php echo $custom_error; ?>
                <form action="<?php echo current_url(); ?>" id="formAquisicao" method="post" class="form-horizontal">
                    <label for="tipo_aquisicao" class="control-label">Tipo<span class="required">*</span></label>
                    <div class="controls">
                        <select id="tipo_aquisicao" name="tipo_aquisicao">
                        <?php foreach ($tipo_aquisicoes as $tipo) : ?>
                            <option value="<?php echo $tipo['idTipoAquisicao']; ?>">
                                <?php echo $tipo['tipoAquisicao']; ?>
                            </option>
                        <?php endforeach ?>
                        </select>
                    </div>
                    <div class="control-group">
                            <label for="idMarca" class="control-label">Marca<span class="required">*</span></label>
                            <div class="controls">
                            <select id="idMarca" name="idMarca">
                                <?php foreach ($marcas as $marca) : ?>
                                    <option value="<?php echo $marca['idMarca']; ?>">
                                    <?php echo $marca['marca']; ?></option>
                                <?php endforeach ?>
                            </select>
                        </div>
                    <div class="control-group">
                        <label for="modelo" class="control-label">Modelo<span class="required">*</span></label>
                        <div class="controls">
                            <input id="modelo" class="span6" type="text" name="modelo" value="" />
                            <input id="idModelo" class="span12" type="hidden" name="idModelo" value="" />
                        </div>
                    </div>
                    <div class="control-group">
                        <label for="dataAquisicao" class="control-label">Data Aquisição<span class="required">*</span></label>
                        <div class="controls">
                        <input id="dataAquisicao" autocomplete="off" class="span2 datepicker" type="text" name="dataAquisicao" value="<?php echo date('d/m/Y'); ?>" />
                        </div>
                    </div>
                    <div class="control-group">
                        <label for="precoCompra" class="control-label">Preço de Compra<span class="required">*</span></label>
                        <div class="controls">
                            <input style="width: 9em;" id="precoCompra" class="money" data-affixes-stay="true" data-thousands="" data-decimal="." type="text" name="precoCompra" value="<?php echo set_value('precoCompra'); ?>" />
                        </div>
                    </div>
                    <div class="span6" style="padding: 1%; margin-left: 0">
                                        <label for="descricaoProduto">
                                            <h4>Descrição Produto</h4>
                                        </label>
                                        <textarea class="span12 editor" name="descricaoProduto" id="descricaoProduto" cols="30" rows="5"></textarea>
                                    </div>
                                    <div class="span6" style="padding: 1%; margin-left: 0">
                                        <label for="defeito">
                                            <h4>Defeito</h4>
                                        </label>
                                        <textarea class="span12 editor" name="defeito" id="defeito" cols="30" rows="5"></textarea>
                                    </div>
                                    <div class="span6" style="padding: 1%; margin-left: 0">
                                        <label for="observacoes">
                                            <h4>Observações</h4>
                                        </label>
                                        <textarea class="span12 editor" name="observacoes" id="observacoes" cols="30" rows="5"></textarea>
                                    </div>
                                    <div class="span6" style="padding: 1%; margin-left: 0">
                                        <label for="laudoTecnico">
                                            <h4>Laudo Técnico</h4>
                                        </label>
                                        <textarea class="span12 editor" name="laudoTecnico" id="laudoTecnico" cols="30" rows="5"></textarea>
                                    </div>
                    <div class="form-actions">
                        <div class="span12">
                            <div style="text-align: center">
                                <button type="submit" class="btn btn-success" id="btnContinuar"><i class="fas fa-plus"></i> Continuar</button>
                                <a href="<?php echo base_url() ?>index.php/aquisicoes" id="" class="btn"><i class="fas fa-backward"></i> Voltar</a>
                            </div>
                        </div>      
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script src="<?php echo base_url() ?>assets/js/jquery.validate.js"></script>
<script src="<?php echo base_url(); ?>assets/js/maskmoney.js"></script>
<script type="text/javascript">
    $(document).ready(function() {
        $(".money").maskMoney();
        $("#modelo").autocomplete({
            source: "<?php echo base_url(); ?>index.php/aquisicoes/autoCompleteModelo",
            minLength: 1,
            select: function(event, ui) {
                $("#idModelo").val(ui.item.id);
            }
        });
        $('#formAquisicao').validate({
            rules: {
                modelo: {
                    required: true
                },
                dataAquisicao: {
                    required: true
                },
                precoCompra: {
                    required: true
                }
            },
            messages: {
                modelo: {
                    required: 'Campo Requerido.'
                },
                dataAquisicao: {
                    required: 'Campo Requerido.'
                },
                precoCompra: {
                    required: 'Campo Requerido.'
                }
            },
            errorClass: "help-inline",
            errorElement: "span",
            highlight: function(element, errorClass, validClass) {
                $(element).parents('.control-group').addClass('error');
            },
            unhighlight: function(element, errorClass, validClass) {
                $(element).parents('.control-group').removeClass('error');
                $(element).parents('.control-group').addClass('success');
            }
            
        });
        $(".datepicker").datepicker({
            dateFormat: 'dd/mm/yy'
        });
    });
</script>
