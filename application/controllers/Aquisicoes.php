<?php

use Mpdf\Tag\S;

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Aquisicoes extends MY_Controller
{

    /**
     * author: Elcio Silva
     * email: elciospy@gmail.com
     *
     */

    public function __construct()
    {
        parent::__construct();

        $this->load->helper('form');
        $this->load->model('aquisicoes_model');
        $this->data['menuAquisicoes'] = 'Aquisicoes';
        $this->data['tipo_aquisicoes'] = $this->aquisicoes_model->autoCompleteTipo();
        $this->data['marcas'] = $this->aquisicoes_model->autoCompleteMarca();

    }

    public function index()
    {
        $this->gerenciar();
    }

    public function gerenciar()
    {
        if (!$this->permission->checkPermission($this->session->userdata('permissao'), 'vAquisicao')) {
            $this->session->set_flashdata('error', 'Você não tem permissão para visualizar aquisicoes.');
            redirect(base_url());
        }

        $this->load->library('pagination');

        $this->data['configuration']['base_url'] = site_url('aquisicoes/gerenciar/');
        $this->data['configuration']['total_rows'] = $this->aquisicoes_model->count('aquisicoes');

        $this->pagination->initialize($this->data['configuration']);

        $this->data['results'] = $this->aquisicoes_model->get('aquisicoes', '*', '', $this->data['configuration']['per_page'], $this->uri->segment(3));
    
        $this->data['view'] = 'aquisicoes/aquisicoes';
        return $this->layout();
        
    }

    public function adicionar()
    {
        if (!$this->permission->checkPermission($this->session->userdata('permissao'), 'aAquisicao')) {
            $this->session->set_flashdata('error', 'Você não tem permissão para adicionar aquisicoes.');
            redirect(base_url());
        }

        $this->load->library('form_validation');
        $this->data['custom_error'] = '';
        
        if ($this->form_validation->run('aquisicoes') == false) {
            $this->data['custom_error'] = (validation_errors() ? '<div class="form_error">' . validation_errors() . '</div>' : false);
        } else {

            $precoCompra = $this->input->post('precoCompra');
            $precoCompra = str_replace(",", "", $precoCompra);
            $dataAquisicao = $this->input->post('dataAquisicao');
            $dataAquisicao = explode('/', $dataAquisicao);
            $dataAquisicao = $dataAquisicao[2] . '-' . $dataAquisicao[1] . '-' . $dataAquisicao[0];

            $data = [
                'idTipoAquisicao'   => set_value('tipo_aquisicao'),
                'idMarca'  => set_value('idMarca'),
                'idModelo' => set_value('idModelo'),
                'dataAquisicao' => $dataAquisicao,
                'precoCompra' => $precoCompra,
                'descricaoProduto' => $this->input->post('descricaoProduto'),
                'defeito' => $this->input->post('defeito'),
                'idAquisicoesStatus' => 1,  /*Default quando a Aquisição é criada é ABERTA */
                'observacoes' => $this->input->post('observacoes'),
                'laudoTecnico' => $this->input->post('laudoTecnico'),
            ];

            if ($this->aquisicoes_model->add('aquisicoes', $data) == true) {
                $this->session->set_flashdata('success', 'Aquisição adicionada com sucesso!');
                log_info('Adicionou uma aquisição');
                redirect(site_url('aquisicoes/adicionar/'));
            } else {
                $this->data['custom_error'] = '<div class="form_error"><p>An Error Occured.</p></div>';
            }
        }
        $this->data['view'] = 'aquisicoes/adicionarAquisicao';
        return $this->layout();
    }

    public function editar()
    {
        if (!$this->uri->segment(3) || !is_numeric($this->uri->segment(3))) {
            $this->session->set_flashdata('error', 'Item não pode ser encontrado, parâmetro não foi passado corretamente.');
            redirect('mapos');
        }

        if (!$this->permission->checkPermission($this->session->userdata('permissao'), 'eAquisicao')) {
            $this->session->set_flashdata('error', 'Você não tem permissão para editar Aquisição');
            redirect(base_url());
        }

        $this->load->library('form_validation');
        $this->data['custom_error'] = '';
        $this->data['texto_de_notificacao'] = $this->data['configuration']['notifica_whats'];

        $this->data['editavel'] = $this->aquisicoes_model->isEditable($this->input->post('idOs'));
        if (!$this->data['editavel']) {
            $this->session->set_flashdata('error', 'Esta Aquisição já e seu status não pode ser alterado e nem suas informações atualizadas. Por favor abrir uma nova Aquisição.');
            redirect(site_url('aquisicoes'));
        }

        if ($this->form_validation->run('aquisicoes') == false) {
            $this->data['custom_error'] = (validation_errors() ? '<div class="form_error">' . validation_errors() . '</div>' : false);
        } else {
            $dataInicial = $this->input->post('dataInicial');
            $dataFinal = $this->input->post('dataFinal');
            $termoGarantiaId = $this->input->post('garantias_id') ?: null;

            try {
                $dataInicial = explode('/', $dataInicial);
                $dataInicial = $dataInicial[2] . '-' . $dataInicial[1] . '-' . $dataInicial[0];

                $dataFinal = explode('/', $dataFinal);
                $dataFinal = $dataFinal[2] . '-' . $dataFinal[1] . '-' . $dataFinal[0];
            } catch (Exception $e) {
                $dataInicial = date('Y/m/d');
            }

            $data = [
                'dataInicial' => $dataInicial,
                'dataFinal' => $dataFinal,
                'garantia' => $this->input->post('garantia'),
                'garantias_id' => $termoGarantiaId,
                'descricaoProduto' => $this->input->post('descricaoProduto'),
                'defeito' => $this->input->post('defeito'),
                'status' => $this->input->post('status'),
                'observacoes' => $this->input->post('observacoes'),
                'laudoTecnico' => $this->input->post('laudoTecnico'),
                'usuarios_id' => $this->input->post('usuarios_id'),
                'clientes_id' => $this->input->post('clientes_id'),
            ];
            $os = $this->aquisicoes_model->getById($this->input->post('idOs'));

            if ($this->aquisicoes_model->edit('os', $data, 'idOs', $this->input->post('idOs')) == true) {
                $this->load->model('mapos_model');
                $this->load->model('usuarios_model');

                $idOs = $this->input->post('idOs');

                $os = $this->aquisicoes_model->getById($idOs);
                $emitente = $this->mapos_model->getEmitente()[0];
                $tecnico = $this->usuarios_model->getById($os->usuarios_id);

                // Verificar configuração de notificação
                if ($this->data['configuration']['os_notification'] != 'nenhum') {
                    $remetentes = [];
                    switch ($this->data['configuration']['os_notification']) {
                        case 'todos':
                            array_push($remetentes, $os->email);
                            array_push($remetentes, $tecnico->email);
                            array_push($remetentes, $emitente->email);
                            break;
                        case 'cliente':
                            array_push($remetentes, $os->email);
                            break;
                        case 'tecnico':
                            array_push($remetentes, $tecnico->email);
                            break;
                        case 'emitente':
                            array_push($remetentes, $emitente->email);
                            break;
                        default:
                            array_push($remetentes, $os->email);
                            break;
                    }
                    $this->enviarOsPorEmail($idOs, $remetentes, 'Ordem de Serviço - Editada');
                }

                $this->session->set_flashdata('success', 'Os editada com sucesso!');
                log_info('Alterou uma OS. ID: ' . $this->input->post('idOs'));
                redirect(site_url('os/editar/') . $this->input->post('idOs'));
            } else {
                $this->data['custom_error'] = '<div class="form_error"><p>Ocorreu um erro</p></div>';
            }
        }

        $this->data['result'] = $this->aquisicoes_model->getById($this->uri->segment(3));
        $this->data['produtos'] = $this->aquisicoes_model->getProdutos($this->uri->segment(3));
        $this->data['servicos'] = $this->aquisicoes_model->getServicos($this->uri->segment(3));
        $this->data['anexos'] = $this->aquisicoes_model->getAnexos($this->uri->segment(3));
        $this->data['anotacoes'] = $this->aquisicoes_model->getAnotacoes($this->uri->segment(3));

        if ($return = $this->aquisicoes_model->valorTotalOS($this->uri->segment(3))) {
            $this->data['totalServico'] = $return['totalServico'];
            $this->data['totalProdutos'] = $return['totalProdutos'];
        }

//        $this->load->model('mapos_model');
//        $this->data['emitente'] = $this->mapos_model->getEmitente();

        $this->data['view'] = 'aquisicoes/editarAquisicao';
        return $this->layout();
    }

    public function visualizar()
    {
        if (!$this->uri->segment(3) || !is_numeric($this->uri->segment(3))) {
            $this->session->set_flashdata('error', 'Item não pode ser encontrado, parâmetro não foi passado corretamente.');
            redirect('mapos');
        }

        if (!$this->permission->checkPermission($this->session->userdata('permissao'), 'vAquisicao')) {
            $this->session->set_flashdata('error', 'Você não tem permissão para visualizar aquisicoes.');
            redirect(base_url());
        }

        $this->data['result'] = $this->aquisicoes_model->getById($this->uri->segment(3));

        if ($this->data['result'] == null) {
            $this->session->set_flashdata('error', 'Aquisição não encontrada.');
            redirect(site_url('aquisicoes/editar/') . $this->input->post('idAquisicao'));
        }

        $this->data['view'] = 'aquisicoes/visualizarAquisicao';
        return $this->layout();
    }

    public function excluir()
    {
        if (!$this->permission->checkPermission($this->session->userdata('permissao'), 'dAquisicao')) {
            $this->session->set_flashdata('error', 'Você não tem permissão para excluir aquisicoes.');
            redirect(base_url());
        }

        $id = $this->input->post('id');
        if ($id == null) {
            $this->session->set_flashdata('error', 'Erro ao tentar excluir aquisição.');
            redirect(base_url() . 'index.php/aquisicoes/gerenciar/');
        }

        $this->aquisicoes_model->delete('aquisicoes_os', 'aquisicoes_id', $id);
        $this->aquisicoes_model->delete('itens_de_vendas', 'aquisicoes_id', $id);
        $this->aquisicoes_model->delete('aquisicoes', 'idAquisicao', $id);

        log_info('Removeu um aquisição. ID: ' . $id);

        $this->session->set_flashdata('success', 'Aquisição excluido com sucesso!');
        redirect(site_url('aquisicoes/gerenciar/'));
    }

    public function atualizar_estoque()
    {
        if (!$this->permission->checkPermission($this->session->userdata('permissao'), 'eAquisicao')) {
            $this->session->set_flashdata('error', 'Você não tem permissão para atualizar estoque de aquisicoes.');
            redirect(base_url());
        }

        $idProduto = $this->input->post('id');
        $novoEstoque = $this->input->post('estoque');
        $estoqueAtual = $this->input->post('estoqueAtual');

        $estoque = $estoqueAtual + $novoEstoque;

        $data = [
            'estoque' => $estoque,
        ];

        if ($this->aquisicoes_model->edit('aquisicoes', $data, 'idAquisicao', $idProduto) == true) {
            $this->session->set_flashdata('success', 'Estoque de Produto atualizado com sucesso!');
            log_info('Atualizou estoque de uma aquisição. ID: ' . $idProduto);
            redirect(site_url('aquisicoes/visualizar/') . $idProduto);
        } else {
            $this->data['custom_error'] = '<div class="alert">Ocorreu um erro.</div>';
        }
    }

    public function autoCompleteModelo()
    {
        if (isset($_GET['term'])) {
            $q = strtolower($_GET['term']);
            $this->aquisicoes_model->autoCompleteModelo($q);
        }
    }

    public function autoCompleteTipo()
    {
        /*
        
        if (isset($_GET['term'])) {
            $q = strtolower($_GET['term']);*/
            $this->aquisicoes_model->autoCompleteTipo();
        /* }*/
    }
    public function autoCompleteMarca()
    {
        /*
        if (isset($_GET['term'])) {
            $q = strtolower($_GET['term']);*/
            $this->aquisicoes_model->autoCompleteMarca();
        /* }*/
    }

    private function enviarOsPorEmail($idOs, $remetentes, $assunto)
    {
        $dados = [];

        $this->load->model('mapos_model');
        $dados['result'] = $this->aquisicoes_model->getById($idOs);
        if (!isset($dados['result']->email)) {
            return false;
        }

        $dados['produtos'] = $this->aquisicoes_model->getProdutos($idOs);
        $dados['servicos'] = $this->aquisicoes_model->getServicos($idOs);
        $dados['emitente'] = $this->mapos_model->getEmitente();

        $emitente = $dados['emitente'][0]->email;
        if (!isset($emitente)) {
            return false;
        }

        $html = $this->load->view('os/emails/os', $dados, true);

        $this->load->model('email_model');

        $remetentes = array_unique($remetentes);
        foreach ($remetentes as $remetente) {
            $headers = ['From' => $emitente, 'Subject' => $assunto, 'Return-Path' => ''];
            $email = [
                'to' => $remetente,
                'message' => $html,
                'status' => 'pending',
                'date' => date('Y-m-d H:i:s'),
                'headers' => serialize($headers),
            ];
            $this->email_model->add('email_queue', $email);
        }

        return true;
    }
}
