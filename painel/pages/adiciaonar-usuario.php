<?php verificaPermissaoPagina(2);
    
?>
<div class="box-container w100" 

<?php
    verificaPermissaoMenu(2);
?>>
    <h2 class="title"><i class="fas fa-user-plus"></i> Adicionar Usuário</h2>
    <hr>

    <form action="" method="post" enctype="multipart/form-data" id="editar-usuario">
 
	<?php
        if(isset($_POST['acao'])){
            //enviado o formulario
            $user = $_POST['user'];
            $nome = $_POST['nome'];
            $senha = $_POST['password'];
            $imagem = $_FILES['imagem'];
            $cargo = $_POST['cargo'];
            //validação
            if($user == ''){
                Painel::alert('erro', 'É Necessário Preencher o Campo Login');
            }else if($nome == ''){
                Painel::alert('erro', 'É Necessário Preencher o Campo Nome');
            }else if($senha == ''){
                Painel::alert('erro', 'É Necessário Preencher o Campo Senha');
            }else if($cargo == ''){
                Painel::alert('erro', 'É Necessário Preencher o Campo Cargo');
            }else{
                //validar o cargo
                if($cargo >= $_SESSION['cargo']){
                     Painel::alert('erro', 'É Necessário Preencher o Campo Cargo');
                }else if(Painel::userExist($user)){
                    Painel::alert('erro', 'O Login já Existe');
                }else if($imagem['name'] != ''){
                    if(Painel::imagemValida($imagem) == false){
                        Painel::alert('erro', 'Insira Uma Imagem Valida');
                    }else{
                        //função cadastra no banco de dados os dados
                        $imagem = Painel::uploadFile($imagem);
                        Painel::addUser($user,$senha,$imagem,$nome,$cargo);
                        
                        Painel::alert('sucesso', 'Cadastrado com Sucesso');
                       
                    }                    
                }else{
                    //função cadastra no banco de dados os dados
                    $imagem = '';
                    Painel::addUser($user,$senha,$imagem,$nome,$cargo);
                    Painel::alert('sucesso', 'Cadastrado com Sucesso');
                   
                }
                
            }
            
            
                
	    }
		?>
        <div class="box-form">
            <label for="user">Login do Usuario:</label>
            <input type="text" name="user" id="user">
        </div>
        <div class="box-form">
            <label for="nome">Nome:</label>
            <input type="text" name="nome" id="nome">
        </div>
        <div class="box-form">
            <label for="pass">Senha:</label>
            <input type="password" name="password" id="pass">
        </div>
        <div class="box-form">
            <label for="cargo">Cargo:</label>
            <select name="cargo" id="cargo">
                <?php
                    foreach(Painel::$cargo as $key => $value){
                       if($key < $_SESSION['cargo']){
                         echo '<option value="'.$key.'">'.$value.'</option>';  
                       } 
                    }
                ?>
            </select>
        </div>
        <div class="box-form">
            <label for="img">Imagem:</label>
            <input type="file" name="imagem" id="img">
        </div>
        <div class="box-form">            
            <input type="submit" name="acao" value="Cadastrar">
        </div>
    </form>
</div>

