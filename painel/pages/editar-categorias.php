<?php
    if(isset($_GET['id'])){
        $id = intval($_GET['id']);
        //$dados = Painel::select("tb_site.categorias", 'id = ?',$id);
        $dados = MySql::connect()->prepare("SELECT * FROM `tb_site.categorias` WHERE  id = ?");
        $dados->execute(array($id));
        $dados = $dados->fetch();
    }else{
        Painel::alert('erro', 'Ocorreu Um Erro');
    }
?>

<div class="box-container w100">
    <h2 class="title"><i class="far fa-edit"></i> Editar Categoria</h2>
    <hr>

    <form action="" method="post" enctype="multipart/form-data" id="editar-usuario">
 <?php
    if(isset($_POST['acao'])){
        $slug = Painel::generateSlug($_POST['nome']);
        $arr = array_merge($_POST,array('slug'=>$slug));
        $verificar = MySql::connect()->prepare("SELECT * FROM `tb_site.categorias` WHERE nome = ? AND id != ?");
        $verificar->execute(array($_POST['nome'],$id));
        if($verificar->rowCount() == 1){
            Painel::alert('erro', 'Já Existe Essa Categoria'); 
        }else{
            if(Painel::update($arr)){
                Painel::alert('sucesso', 'Categoria Editado com Sucesso');
                $dados = Painel::selectSimples("tb_site.categorias",$id);
            }else{
                Painel::alert('erro', 'Ocorreu um Erro ao Editado'); 
            }
        }
        
        
    }
 ?>
 <?php
    
    ?>
        <div class="box-form">
            <label for="nome">Categoria:</label>
            <input type="text" name="nome" id="nome" value="<?php echo $dados['nome'] ?>">
        </div>
               
        <div class="box-form">
            <input type="hidden" name="id" value="<?php echo $dados['id']; ?>"> 
            <input type="hidden" name="order_id" value="<?php echo $dados['order_id']; ?>"> 
            <input type="hidden" name="nome_tabela" value="tb_site.categorias">        
            <input type="submit" name="acao" value="Editar">
        </div>
    </form>
</div>

