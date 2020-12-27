<?php 

require_once("../conexao.php");

$nome = filtraEntrada($_POST['nome']);
$cpf = filtraEntrada($_POST['cpf']);
$telefone = filtraEntrada($_POST['telefone']);
$email = filtraEntrada($_POST['email']);
$senha = filtraEntrada($_POST['senha']);
$senha_crip = filtraEntrada(md5($senha));
$data_cadastro = dataAtual();



if($nome == ""){
	echo 'Preencha o campo nome!';
	exit();
}

if($cpf == ""){
	echo 'Preencha o campo cpf!';
	exit();
}

if(!validaCPF($cpf)){
    echo "CPF inválido";
    exit();
}

if($telefone == ""){
	echo 'Preencha o campo Whatsapp!';
	exit();
}

if($email == ""){
	echo 'Preencha o campo email!';
	exit();
}

if($senha == ""){
	echo 'Preencha o campo senha!';
	exit();
}

if($senha != $_POST['confirmar-senha']){
	echo 'As senhas não coincidem!';
	exit();
}

//REMOVENDO MASCARA DO CPF
$cpf = removeMascCPF($cpf);

//REMOVENDO MASCARA DO TELEFONE
$telefone = removeMascTel($telefone);

if(!celular($telefone)){
	echo 'Ops! faltou DDD ou numeros';
	exit();
}

$res = $pdo->query("SELECT * FROM usuarios where cpf = '$cpf' or email = '$email'"); 
$dados = $res->fetchAll(PDO::FETCH_ASSOC);
if(@count($dados) == 0){

	$res = $pdo->prepare("INSERT into usuarios (nome, cpf, email, senha, senha_crip, nivel) values (:nome, :cpf, :email, :senha, :senha_crip, :nivel)");
	$res->bindValue(":nome", $nome);
	$res->bindValue(":email", $email);
	$res->bindValue(":cpf", $cpf);
	$res->bindValue(":senha", $senha);
	$res->bindValue(":senha_crip", $senha_crip);
	$res->bindValue(":nivel", 'Cliente');

	$res->execute();


	$res = $pdo->prepare("INSERT into clientes (nome, cpf, telefone, email, data_cadastro) values (:nome, :cpf, :telefone, :email, :data_cadastro)");
	$res->bindValue(":nome", $nome);
	$res->bindValue(":email", $email);
    $res->bindValue(":data_cadastro", $data_cadastro);
    $res->bindValue(":telefone", $telefone);
	$res->bindValue(":cpf", $cpf);
	
	$res->execute();


	$res = $pdo->query("SELECT * FROM emails where email = '$email'"); 
	$dados = $res->fetchAll(PDO::FETCH_ASSOC);
	if(@count($dados) == 0){
	$res = $pdo->prepare("INSERT into emails (nome, email, ativo) values (:nome, :email, :ativo)");
	$res->bindValue(":nome", $nome);
	$res->bindValue(":email", $email);
	$res->bindValue(":ativo", "Sim");
	$res->execute();
}


	echo 'Cadastrado com Sucesso!';
}else{
	echo 'CPF ou E-mail já cadastrado!';
}


?>