<div class="accordion" id="collapse-group">
    <div class="accordion-group widget-box">
        <div class="accordion-heading">
            <div class="widget-title">
                <a data-parent="#collapse-group" href="#collapseGOne" data-toggle="collapse">
                    <span class="icon"><i class="fas fa-shopping-bag"></i></span>
                    <h5>Dados da Aquisição</h5>
                </a>
            </div>
        </div>
        <div class="collapse in accordion-body">
            <div class="widget-content">
                <table class="table table-bordered">
                    <tbody>
                        <tr>
                            <td style="text-align: right; width: 30%"><strong>ID</strong></td>
                            <td>
                                <?php 
                                echo $result->idAquisicao ?>
                            </td>
                        </tr>
                        <tr>
                            <td style="text-align: right; width: 30%"><strong>Aquisição</strong></td>
                            <td>
                                <?php
                                    echo $result->tipoAquisicao . ' ' . $result->marca . ' '. $result->modelo; 
                                ?>
                            </td>
                        </tr>
                        <tr>
                            <td style="text-align: right"><strong>Data de Aquisição</strong></td>
                            <td>
                                <?php
                                    echo $dataAquisicao = date(('d/m/Y'), strtotime($result->dataAquisicao)); 
                                ?>
                            </td>
                        </tr>
                        <tr>
                            <td style="text-align: right"><strong>Preço de Compra</strong></td>
                            <td>R$
                                <?php echo $result->precoCompra; ?>
                            </td>
                        </tr>
                        <tr>
                            <td style="text-align: right"><strong>Descricao</strong></td>
                            <td>
                                <?php echo $result->descricaoProduto; ?>
                            </td>
                        </tr>
                        <tr>
                            <td style="text-align: right"><strong>Defeito</strong></td>
                            <td>
                                <?php echo $result->defeito; ?>
                            </td>
                        </tr>
                        <tr>
                            <td style="text-align: right"><strong>Observações</strong></td>
                            <td>
                                <?php echo $result->observacoes; ?>
                            </td>
                        </tr>
                        <tr>
                            <td style="text-align: right"><strong>Laudo Técnico</strong></td>
                            <td>
                                <?php echo $result->laudoTecnico; ?>
                            </td>
                        </tr>
                    </tbody>
                </table>
                <div class="form-actions">
                    <div class="span12">                                    
                        <div style="text-align: center">
                            <a href="<?php echo base_url() ?>index.php/aquisicoes" id="" class="btn"><i class="fas fa-backward"></i> Voltar</a>
                        </div>
                    </div>      
                </div>
            </div>
        </div>
    </div>
</div>
