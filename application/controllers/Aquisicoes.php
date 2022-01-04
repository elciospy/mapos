<?php if (!defined('BASEPATH')) {
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
                'idMarca'  => set_value('marca'),
                'idModelo' => set_value('idModelo'),
                'dataAquisicao' => $dataAquisicao,
                'precoCompra' => $precoCompra
            ];

            if ($this->aquisicoes_model->add('aquisicoes', $data) == true) {
                $this->session->set_flashdata('success', 'Produto adicionado com sucesso!');
                log_info('Adicionou um produto');
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

        if (!$this->permission->checkPermission($this->session->userdata('permissao'), 'eProduto')) {
            $this->session->set_flashdata('error', 'Você não tem permissão para editar aquisicoes.');
            redirect(base_url());
        }
        $this->load->library('form_validation');
        $this->data['custom_error'] = '';

        if ($this->form_validation->run('aquisicoes') == false) {
            $this->data['custom_error'] = (validation_errors() ? '<div class="form_error">' . validation_errors() . '</div>' : false);
        } else {
            $precoCompra = $this->input->post('precoCompra');
            $precoCompra = str_replace(",", "", $precoCompra);
            $precoVenda = $this->input->post('precoVenda');
            $precoVenda = str_replace(",", "", $precoVenda);
            $data = [
                'codDeBarra' => set_value('codDeBarra'),
                'descricao' => $this->input->post('descricao'),
                'unidade' => $this->input->post('unidade'),
                'precoCompra' => $precoCompra,
                'precoVenda' => $precoVenda,
                'estoque' => $this->input->post('estoque'),
                'estoqueMinimo' => $this->input->post('estoqueMinimo'),
                'saida' => set_value('saida'),
                'entrada' => set_value('entrada'),
            ];

            if ($this->aquisicoes_model->edit('aquisicoes', $data, 'idAquisicao', $this->input->post('idAquisicao')) == true) {
                $this->session->set_flashdata('success', 'Produto editado com sucesso!');
                log_info('Alterou um produto. ID: ' . $this->input->post('idAquisicao'));
                redirect(site_url('aquisicoes/editar/') . $this->input->post('idAquisicao'));
            } else {
                $this->data['custom_error'] = '<div class="form_error"><p>An Error Occured</p></div>';
            }
        }

        $this->data['result'] = $this->aquisicoes_model->getById($this->uri->segment(3));

        $this->data['view'] = 'aquisicoes/editarProduto';
        return $this->layout();
    }

    public function visualizar()
    {
        if (!$this->uri->segment(3) || !is_numeric($this->uri->segment(3))) {
            $this->session->set_flashdata('error', 'Item não pode ser encontrado, parâmetro não foi passado corretamente.');
            redirect('mapos');
        }

        if (!$this->permission->checkPermission($this->session->userdata('permissao'), 'vProduto')) {
            $this->session->set_flashdata('error', 'Você não tem permissão para visualizar aquisicoes.');
            redirect(base_url());
        }

        $this->data['result'] = $this->aquisicoes_model->getById($this->uri->segment(3));

        if ($this->data['result'] == null) {
            $this->session->set_flashdata('error', 'Produto não encontrado.');
            redirect(site_url('aquisicoes/editar/') . $this->input->post('idAquisicao'));
        }

        $this->data['view'] = 'aquisicoes/visualizarProduto';
        return $this->layout();
    }

    public function excluir()
    {
        if (!$this->permission->checkPermission($this->session->userdata('permissao'), 'dProduto')) {
            $this->session->set_flashdata('error', 'Você não tem permissão para excluir aquisicoes.');
            redirect(base_url());
        }

        $id = $this->input->post('id');
        if ($id == null) {
            $this->session->set_flashdata('error', 'Erro ao tentar excluir produto.');
            redirect(base_url() . 'index.php/aquisicoes/gerenciar/');
        }

        $this->aquisicoes_model->delete('aquisicoes_os', 'aquisicoes_id', $id);
        $this->aquisicoes_model->delete('itens_de_vendas', 'aquisicoes_id', $id);
        $this->aquisicoes_model->delete('aquisicoes', 'idAquisicao', $id);

        log_info('Removeu um produto. ID: ' . $id);

        $this->session->set_flashdata('success', 'Produto excluido com sucesso!');
        redirect(site_url('aquisicoes/gerenciar/'));
    }

    public function atualizar_estoque()
    {
        if (!$this->permission->checkPermission($this->session->userdata('permissao'), 'eProduto')) {
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
            log_info('Atualizou estoque de um produto. ID: ' . $idProduto);
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
}
