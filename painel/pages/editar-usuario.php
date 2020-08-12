<div class="box-container w100">
    <h2 class="title"><i class="fas fa-user-edit"></i> Editar Usuário</h2>
    <hr>

    <form action="" method="post" enctype="multipart/form-data" id="editar-usuario">
 
	<?php
        if(isset($_POST['acao'])){
            //enviado o formulario
            $nome = $_POST['nome'];
            $senha = $_POST['password'];
            $imagem = $_FILES['imagem'];
            $imagem_atual = $_POST['imagem_atual'];
            $usuario = new Usuario();

            if($imagem['name'] != ''){
                //existe um upload de imagem
               if(Painel::imagemValida($imagem)){           
                Painel::deleteFile($imagem_atual);
                $imagem = Painel::uploadFile($imagem);
                if($usuario->atualizarUsuario($nome,$senha,$imagem)){
                    $_SESSION['img'] = $imagem;
                    Painel::alert('sucesso', 'Atualisado com Sucesso');
                }else{
                    Painel::alert('erro', 'Ocorreu Um Erro');
                }
               }else{
                    Painel::alert('erro', 'O formato não é valido');
               }
            }
            else{
                $imagem = $imagem_atual;
                if($usuario->atualisarUsuario($nome,$senha,$imagem)){
                    Painel::alert('sucesso', 'Enviado com Sucesso');
                }else{
                    Painel::alert('erro', 'Ocorreu Um Erro');
                }
            }        
	    }
		?>
        <div class="box-form">
            <label for="nome">Nome:</label>
            <input type="text" name="nome" require value="<?php echo $_SESSION['nome'] ?>">
        </div>
        <div class="box-form">
            <label for="pass">Senha:</label>
            <input type="password" name="password" require value="<?php echo $_SESSION['password'] ?>">
        </div>
        <div class="box-form">
            <label for="img">Imagem:</label>
            <input type="file" name="imagem">
            <input type="hidden" name="imagem_atual" value="<?php echo $_SESSION['img'] ?>">
        </div>
        <div class="box-form">            
            <input type="submit" name="acao" value="Atualizar">
        </div>
    </form>
</div>

