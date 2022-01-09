<?php
class Aquisicoes_model extends CI_Model
{

    /**
     * author: Elcio Silva
     * email: elciospy@gmail.com
     *
     */
    
    public function __construct()
    {
        parent::__construct();
    }

    
    public function get($table, $fields, $where = '', $perpage = 0, $start = 0, $one = false, $array = 'array')
    {
        $this->db->select(
            $fields . 
            ',tipo_aquisicao.tipoAquisicao'.
            ',marcas.marca'.
            ',modelos.modelo'
        );
        $this->db->from($table);
        $this->db->join('tipo_aquisicao', 'tipo_aquisicao.idTipoAquisicao = aquisicoes.idTipoAquisicao');
        $this->db->join('modelos', 'modelos.idModelo = aquisicoes.idModelo');
        $this->db->join('marcas', 'marcas.idMarca = aquisicoes.idMarca');

        $this->db->order_by('idAquisicao', 'desc');
        $this->db->limit($perpage, $start);
        if ($where) {
            $this->db->where($where);
        }
      
        $query = $this->db->get();
        
        $result =  !$one  ? $query->result() : $query->row();
        return $result;
    }

    public function getById($id)
    {
        $this->db->select(
            '*' . 
            ',tipo_aquisicao.tipoAquisicao'.
            ',marcas.marca'.
            ',modelos.modelo'
        );
        $this->db->join('tipo_aquisicao', 'tipo_aquisicao.idTipoAquisicao = aquisicoes.idTipoAquisicao');
        $this->db->join('modelos', 'modelos.idModelo = aquisicoes.idModelo');
        $this->db->join('marcas', 'marcas.idMarca = aquisicoes.idMarca');
        $this->db->where('idAquisicao', $id);
        $this->db->limit(1);
        return $this->db->get('aquisicoes')->row();
    }
    public function getProdutos($id = null)
    {
        $this->db->select('produtos_os.*, produtos.*');
        $this->db->from('produtos_os');
        $this->db->join('produtos', 'produtos.idProdutos = produtos_os.produtos_id');
        $this->db->where('os_id', $id);

        return $this->db->get()->result();
    }

    public function getServicos($id = null)
    {
        $this->db->select('servicos_os.*, servicos.nome, servicos.preco as precoVenda');
        $this->db->from('servicos_os');
        $this->db->join('servicos', 'servicos.idServicos = servicos_os.servicos_id');
        $this->db->where('os_id', $id);

        return $this->db->get()->result();
    }
    public function getAnexos($os)
    {
        $this->db->where('os_id', $os);
        return $this->db->get('anexos')->result();
    }

    public function getAnotacoes($os)
    {
        $this->db->where('os_id', $os);
        $this->db->order_by('idAnotacoes', 'desc');

        return $this->db->get('anotacoes_os')->result();
    }

    public function valorTotalOS($id = null)
    {
        $totalServico = 0;
        $totalProdutos = 0;
        if ($servicos = $this->getServicos($id)) {
            foreach ($servicos as $s) {
                $preco = $s->preco ?: $s->precoVenda;
                $totalServico = $totalServico + ($preco * ($s->quantidade ?: 1));
            }
        }
        if ($produtos = $this->getProdutos($id)) {
            foreach ($produtos as $p) {
                $totalProdutos = $totalProdutos + $p->subTotal;
            }
        }

        return ['totalServico' => $totalServico, 'totalProdutos' => $totalProdutos];
    }

    public function add($table, $data)
    {
        $this->db->insert($table, $data);
        if ($this->db->affected_rows() == '1') {
            return true;
        }
        
        return false;
    }
    
    public function edit($table, $data, $fieldID, $ID)
    {
        $this->db->where($fieldID, $ID);
        $this->db->update($table, $data);

        if ($this->db->affected_rows() >= 0) {
            return true;
        }
        
        return false;
    }
    
    public function delete($table, $fieldID, $ID)
    {
        $this->db->where($fieldID, $ID);
        $this->db->delete($table);
        if ($this->db->affected_rows() == '1') {
            return true;
        }
        
        return false;
    }
    
    public function count($table)
    {
        return $this->db->count_all($table);
    }

    public function updateEstoque($produto, $quantidade, $operacao = '-')
    {
        $sql = "UPDATE aquisicao set estoque = estoque $operacao ? WHERE idAquisicao = ?";
        return $this->db->query($sql, [$quantidade, $produto]);
    }

    public function autoCompleteModelo($q)
    {
        $this->db->select('*');
        $this->db->limit(5);
        $this->db->like('modelo', $q);
        $query = $this->db->get('modelos');
        if ($query->num_rows() > 0) {
            foreach ($query->result_array() as $row) {
                $row_set[] = ['label' => $row['modelo'], 'id' => $row['idModelo']];
            }
            echo json_encode($row_set);
        }
    }
    public function autoCompleteTipo()
    {
        $this->db->select('*');
        $query = $this->db->get('tipo_aquisicao');
        return $query->result_array();
    }
    public function autoCompleteMarca()
    {
        $this->db->select('*');
        $query = $this->db->get('marcas');
        return $query->result_array();
    }
    public function isEditable($id = null)
    {
        if (!$this->permission->checkPermission($this->session->userdata('permissao'), 'eAquisicao')) {
            return false;
        }
        if ($os = $this->getById($id)) {
            $osT = (int)($os->status === "Faturado" || $os->status === "Cancelado" || $os->faturado == 1);
            if ($osT) {
                return $this->data['configuration']['control_editos'] == '1';
            }
        }
        return true;
    }
}
