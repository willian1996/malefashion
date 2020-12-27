
<?php 

require_once("../../../conexao.php"); 

$id_venda = $_POST['idvenda'];

$query_v = $pdo->query("SELECT * FROM vendas where id = '$id_venda' ");
$res_v = $query_v->fetchAll(PDO::FETCH_ASSOC);
$id_usu = $res_v[0]['id_usuario'];

$query_u = $pdo->query("SELECT * FROM usuarios where id = '$id_usu' ");
$res_u = $query_u->fetchAll(PDO::FETCH_ASSOC);
$cpf_usu = $res_u[0]['cpf'];

$query = $pdo->query("SELECT * FROM clientes where cpf = '$cpf_usu' ");
$res = $query->fetchAll(PDO::FETCH_ASSOC);

$nome = $res[0]['nome'];
$cpf = $res[0]['cpf'];
$telefone = $res[0]['telefone'];
$rua = $res[0]['rua'];
$numero = $res[0]['numero'];
$cep = $res[0]['cep'];
$bairro = $res[0]['bairro'];
$cidade = $res[0]['cidade'];
$estado = $res[0]['estado'];
$email_cli = $res[0]['email'];

?>
<span><b>Nome: </b><?php echo $nome ?> </span><br>
<span><b>CPF: </b> <?php echo $cpf ?></span><br>
<span><b>Email:</b> <?php echo $email_cli ?></span><br>
<span><b>Whatsapp: </b> <a href="https://api.whatsapp.com/send?phone=55<?php echo $telefone."&text=Oi%20$nome%20" ?>" target="_blank"><?php echo $telefone ?></a></span><hr>

<span><b>Endereço:</b> <?php echo $rua.', '. $numero.', '.$bairro ?> </span><br>
<span><b>Cidade: </b> <?php echo $cidade ?></span><br>

<span><b>Estado: </b> <?php echo $estado ?></span><br>
<span><b>CEP: </b> <?php echo $cep ?></span><br><br>


<?php

//TABELA ITENS DA VENDA
echo '
<table class="table table-hover">
  <thead>
    <tr>
      <th scope="col">Img</th>
      <th scope="col">Produto</th>
      <th scope="col">Codigo</th>
      <th scope="col">Quant</th>
      <th scope="col">Valor</th>
      <th scope="col">Subtotal</th>
      
    </tr>
  </thead>
  <tbody>
';
//PEGANDO OS ITENS DO CARRINHO 
$res = $pdo->query("SELECT * from carrinho where id_venda = '$id_venda' ");
$dados = $res->fetchAll(PDO::FETCH_ASSOC);
for ($i=0; $i < count($dados); $i++) { 
 foreach ($dados[$i] as $key => $value) {
 }

 $id_produto = $dados[$i]['id_produto'];
  $id_carrinho = $dados[$i]['id'];	
  $combo = $dados[$i]['combo'];
$quantidade = $dados[0]['quantidade'];

if($combo != 'Sim'){
	$res2 = $pdo->query("SELECT * from produtos where id = '$id_produto' ");
}else{
	$res2 = $pdo->query("SELECT * from combos where id = '$id_produto' ");
}



$dados2 = $res2->fetchAll(PDO::FETCH_ASSOC);
$nome_produto = $dados2[0]['nome'];
$valor = $dados2[0]['valor'];
$sub_total_item = $quantidade * $valor;
$imagem = $dados2[0]['imagem'];
$codigo = $dados2[0]['id'];

$sub_total = number_format( $sub_total_item , 2, ',', '.');
$valor = number_format( $valor , 2, ',', '.');
                          
echo '<td><img src="../../img/produtos/'. $imagem .'" width="50"> </td>';

echo '<td>'.$nome_produto.'<br>';
    

    
if($combo != 'Sim'){
	$query_c = $pdo->query("SELECT * from carac_prod where id_prod = '$id_produto'");
	$res_c = $query_c->fetchAll(PDO::FETCH_ASSOC);
	$total_prod_carac = @count($res_c);


	if($total_prod_carac > 0){

	   
	   $query4 = $pdo->query("SELECT * from carac_itens_car where id_carrinho = '$id_carrinho'");
	  $res4 = $query4->fetchAll(PDO::FETCH_ASSOC);
	  
	  for ($i2=0; $i2 < count($res4); $i2++) { 
	      foreach ($res4[$i2] as $key => $value) {
	  }


	  $nome_item_carac = $res4[$i2]['nome'];
	  $id_carac = $res4[$i2]['id_carac'];

	  $query1 = $pdo->query("SELECT * from carac where id = '$id_carac' ");
	  $res1 = $query1->fetchAll(PDO::FETCH_ASSOC);
	  $nome_carac = $res1[0]['nome'];


	    echo '<u><i><small><span class="mr-2 ml-2"><i class="mr-1 fa fa-check text-info"></i>'.$nome_carac.' : '.$nome_item_carac.'</span></small></i></u>';

	  }
	} 
}else{
	$query = $pdo->query("SELECT * FROM prod_combos where id_combo = '$id_produto' ");
                    $res = $query->fetchAll(PDO::FETCH_ASSOC);

                    for ($i=0; $i < count($res); $i++) { 
                      foreach ($res[$i] as $key => $value) {
                      }

                      $id_prod = $res[$i]['id_produto'];
                      $query2 = $pdo->query("SELECT * FROM produtos where id = '$id_prod' ");
                    $res2 = $query2->fetchAll(PDO::FETCH_ASSOC);
                    $nome_prod = $res2[0]['nome'];

     echo '<u><i><small><span class="mr-1 ml-2"><i class="mr-1 fa fa-check text-info"></i>'.$nome_prod.'</span></small></i></u>';
 	}
}
    
echo '</td>';
    
echo '<td>'.$codigo.'</td>';

echo '<td>'.$quantidade.'</td>';

echo '<td>R$ '.$valor.'</td>';

echo '<td>R$ '.$sub_total_item.'</td></tr>';




}

echo ' 

</table>  
<hr><br>
';
echo '<div class="row">';
echo '<div class="col-md-4 text-info"><h4>Subtotal R$ '.$res_v[0]['sub_total'].'<h4></div>';
echo '<div class="col-md-4"><h4>Frete R$ '.$res_v[0]['frete'].'<h4></div>';
echo '<div class="col-md-4 text-danger"><h4>Total R$ '.$res_v[0]['total'].'<h4></div>';
echo "</div>";
?>


