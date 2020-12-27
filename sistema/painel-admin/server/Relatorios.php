<?php

new Relatorios();

class Relatorios{
    private $pdo;
    private $data_atual;

    public function __construct(){
        $option = [PDO::MYSQL_ATTR_INIT_COMMAND => "SET LC_TIME_NAMES = pt_BR"];
        $this->pdo = new PDO("mysql:host=localhost;dbname=loja;charset=utf8", "root", "", $option);
        $this->data_atual = date('Y-m-d H:i:s');

        $uri = urldecode(filter_input(INPUT_SERVER, 'REQUEST_URI'));
        $request = explode('/', $uri);
        $method = isset($request[3]) ? $request[3] : null;
        $param = isset($request[4]) ? $request[4] : null;


        if(method_exists(get_class(), $method)){
            $this->$method($param);

        }else{
            return false;
        }
    }

    public function cadastros_mensal(){
        $mes = date('m');
        $ano = date('Y');

        $sql = "SELECT DAY(data_cadastro) as dia, COUNT(id) as registro "
              ."FROM clientes "
              ."WHERE MONTH(data_cadastro) = '{$mes}' "
              ."AND YEAR(data_cadastro) = '{$ano}' "
              ."GROUP BY DAY(data_cadastro) ";

        $query = $this->pdo->query($sql);
        $result = $query->fetchAll(PDO::FETCH_OBJ);

        foreach($result as $res){
            $dados[$res->dia] = $res->registro;

        }

        $dias_do_mes = $this->_dias_do_mes();

        $final = array_replace($dias_do_mes, $dados);

        echo json_encode($final);

    }
    public function vendas_mensal($aprovado){
        $mes = date('m');
        $ano = date('Y');
        $aprovado = $aprovado;

        $sql = "SELECT DAY(data) as dia, COUNT(id) as registro "
              ."FROM vendas "
              ."WHERE MONTH(data) = '{$mes}' "
              ."AND YEAR(data) = '{$ano}' "
              ."AND pago = '$aprovado' "
              ."GROUP BY DAY(data) ";

        $query = $this->pdo->query($sql);
        $result = $query->fetchAll(PDO::FETCH_OBJ);

        foreach($result as $res){
            $dados[$res->dia] = $res->registro;

        }

        $dias_do_mes = $this->_dias_do_mes();

        $final = array_replace($dias_do_mes, $dados);

        echo json_encode($final);
        
    }

    private function _dias_do_mes(){
        $hoje = date('d');

        if($hoje <= 10){
            $esse_mes = 10;

        }else if($hoje <= 15){
            $esse_mes = 15;

        }else if($hoje <= 20){
            $esse_mes = 20;

        }else if($hoje <= 25){
            $esse_mes = 25;

        }else{
            $esse_mes = cal_days_in_month(CAL_GREGORIAN, date('m'), date('Y'));
        }



        $dias = [1 => '0'];
        for($i = 1; $i < $esse_mes; $i++){
            array_push($dias, '0');

        }
        return $dias;
    }
    
    public function cadastros_semestral(){
        $periodo = date('Y-m-d H:i:s', strtotime('-6 months'));
        
        $sql = "SELECT MONTHNAME(data_cadastro) as mes, COUNT(id) as registro "
              ."FROM clientes "
              ."WHERE data_cadastro >= '{$periodo}' "
              ."GROUP BY MONTHNAME(data_cadastro) "
              ."ORDER BY data_cadastro";
              
        $query = $this->pdo->query($sql);
        $result = $query->fetchAll(PDO::FETCH_OBJ);
        
        foreach($result as $res){
            $dados[$res->mes] = $res->registro;

        }
        
        echo json_encode($dados);
    }
    
    public function vendas_anual(){
//        $periodo = date('Y-m-d H:i:s', strtotime('-6 months'));
//        
//        $sql = "SELECT MONTHNAME(cli_data_cad) as mes, COUNT(cli_id) as registro "
//              ."FROM gm_clientes "
//              ."WHERE cli_data_cad >= '{$periodo}' "
//              ."GROUP BY MONTHNAME(cli_data_cad) "
//              ."ORDER BY cli_data_cad";
//              
//        $query = $this->pdo->query($sql);
//        $result = $query->fetchAll(PDO::FETCH_OBJ);
//        
//        foreach($result as $res){
//            $dados[$res->mes] = $res->registro;
//
//        }
//        
//        echo json_encode($dados);
        $periodo = date('Y-m-d', strtotime('-12 months'));
        
        $sql = "SELECT MONTHNAME(data) as mes, COUNT(id) as registro "
               ."FROM vendas "
               ."WHERE data >= '{$periodo}' "
               ."GROUP BY MONTHNAME(data) "
               ."ORDER BY data";
        
        $query = $this->pdo->query($sql);
        $result = $query->fetchAll(PDO::FETCH_OBJ);
        
        foreach($result as $res){
            $dados[$res->mes] = $res->registro;

        }
        
        echo json_encode($dados);
    }
        
        
        
        
    
//    public function cadastros_por_cidade(){
//        $sql = "SELECT cli_cidade, COUNT(cli_id) as registro FROM gm_clientes GROUP BY cli_cidade";
//        $sql = $this->pdo->query($sql);
//        $result = $sql->fetchAll(PDO::FETCH_OBJ);
//        
//        
//        
//        foreach($result as $res){
//            $dados[$res->cli_cidade] = $res->registro;
//            
//        }
//        
//        
//        echo json_encode($dados);
//
//    }
    
//    public function totalCadastros(){
//        $sql = "SELECT COUNT(cli_id) as total FROM gm_clientes";
//        $sql = $this->pdo->query($sql);
//        $total = $sql->fetch();
//        
//        return $total['total'];
//        
//    }
    
//    public function totalVendas(){
//        $sql = "SELECT COUNT(ped_id) as total FROM gm_pedidos";
//        $sql = $this->pdo->query($sql);
//        $total = $sql->fetch();
//        
//        return $total['total'];
//    }








}
