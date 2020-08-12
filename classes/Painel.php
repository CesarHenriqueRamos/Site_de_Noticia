<?php
class Painel{
    public static $cargo = [
        '0' => 'Normal',
        '1' => 'SubAdministrador',
        '2' => 'Administrador'
    ];
    public static function logado(){
        return isset($_SESSION['login'])? true : false;
    }
    public static function logout(){
        setcookie('lembrar','true',time()-1,'/');
        session_destroy();
        header('Location: '.INCLUDE_PATH_PAINEL);
        die();
    }
    public static function carregarPagine(){
        if(isset($_GET['url'])){
            $url = explode('/',$_GET['url']);
            if(file_exists('pages/'.$url[0].'.php')){
                include('pages/'.$url[0].'.php');
            }else{
                //quando a pagina não existe
                header('Location: '.INCLUDE_PATH_PAINEL);
                die();
            }
        }else{
            include('pages/home.php');
        }
    }
    public static function alert($tipe,$mesagem){
        if($tipe == 'sucesso'){
            echo '<div class="sucesso"><i class="fas fa-check"></i> '.$mesagem.'</div>';
        }else if($tipe == 'erro'){
            echo '<div class="err"> <i class="fas fa-times"></i> '.$mesagem.'</div>';
        }
    }
    public static function imagemValida($imagem){
        if($imagem['type'] == 'image/jpeg' || $imagem['type'] == 'image/jpg' || $imagem['type'] == 'image/png'){
            $tamanho = intval($imagem['size']/1024);
            if($tamanho < 300){
                return true;
            }else{
                return false;
            }            
        }else{
            return false;
        }
    }
   
    public static function uploadFile($file){
        $formatoArquivo = explode('.',$file['name']);
        $imagemNome = uniqid().'.'.$formatoArquivo[count($formatoArquivo) - 1];
        if(move_uploaded_file($file['tmp_name'],BASE_DIR_PAINEL.'/uploads/'.$imagemNome)){
            return $imagemNome;
        }else{
            return false;
        }
     }
    public static function deleteFile($file){
        @unlink('uploads/'.$file);
    }
    //validar se o osuario existe
   public static function userExist($user){
       $sql = MySql::connect()->prepare("SELECT `id` FROM `tb_admin.usuarios` WHERE user = ?");
       $sql->execute(array($user));
       if($sql->rowCount() == 1){
           return true;
       }else{
           return false;
       }
   }
   public static function addUser($user,$senha,$imagem,$nome,$cargo){
    $sql = MySql::connect()->prepare("INSERT INTO `tb_admin.usuarios` VALUES(null,?,?,?,?,?)");
    $sql->execute(array($user,$senha,$imagem,$nome,$cargo));
   }
   //função dimamica para inserção de dados
   public static function insert($arr){
       $certo = true;
       $nomeTabela = $arr['nome_tabela'];
       $query = "INSERT INTO `$nomeTabela` VALUES(null";
       foreach ($arr as $key => $value) {
           $nome = $key;
           $valor = $value;
           if($nome == 'acao' || $nome == 'nome_tabela'){
                continue;
           }if($valor == ''){
               $certo = false;
            break;
           }
           $query.=",?";
           $parametros[] = $value;
       }
       $query.= ")";
       if($certo == true){
            $sql = MySql::connect()->prepare($query);
            $sql->execute($parametros);
            $lastId = MySql::connect()->lastInsertId();
            $sql = MySql::connect()->prepare("UPDATE `$nomeTabela` SET order_id = ? WHERE id = $lastId");
            $sql->execute(array($lastId));

       }
       return $certo;
   }
   //update
   public static function update($arr){
    $certo = true;
    $nomeTabela = $arr['nome_tabela'];
    $first = false;
    $query = "UPDATE `$nomeTabela` SET ";
    foreach ($arr as $key => $value) {
        $nome = $key;
        $valor = $value;
        if($nome == 'acao' || $nome == 'nome_tabela' || $nome == 'id'){
             continue;
        }if($valor == ''){
            $certo = false;
         break;
        }
        if($first == false){
            $first = true;
            $query.="$nome=?";
        }else{
            $query.=",$nome=?";
        }
        
        $parametros[] = $value;
    }
    if($certo == true){
        $parametros[] = $arr['id'];
         $sql = MySql::connect()->prepare($query." WHERE id = ? ");
         $sql->execute($parametros);
    }
    return $certo;
}
   //buscar dados padrao
   public static function selectAll($tabela, $start= null, $end = null){
       if($start == null && $end == null){
          $sql = MySql::connect()->prepare("SELECT * FROM `$tabela` ORDER BY order_id ASC"); 
       }else{
        $sql = MySql::connect()->prepare("SELECT * FROM `$tabela` ORDER BY order_id ASC LIMIT $start , $end");  
       }
        $sql->execute();
        
        return $sql->fetchAll();
   }
   //classe especial
   public static function selectExept($tabela,$query,$arr=null, $start= null, $end = null){
    if($start == null && $end == null){
       $sql = MySql::connect()->prepare("SELECT * FROM `$tabela` ORDER BY order_id ASC"); 
    }else{
     $sql = MySql::connect()->prepare("SELECT * FROM `$tabela` WHERE $query ORDER BY order_id ASC LIMIT $start , $end");  
    }
     $sql->execute(array($arr));
     
     return $sql->fetchAll();
}
//fim
   public static function deletar($tabela,$id = false){
        if($id == false){
            $sql = MySql::connect()->prepare("DELETE FROM `$tabela`"); 
        }else{
            $sql = MySql::connect()->prepare("DELETE FROM `$tabela` WHERE id=$id"); 
        }
        $sql->execute();
   }
    public static function redirect($url){
        echo '<script>location.href="'.$url.'"</script>';
		die();
    }
    //metodo para selecionar apena um registro
    public static function select($tabela,$query,$arr){
        $sql = MySql::connect()->prepare("SELECT * FROM `$tabela` WHERE $query"); 
        $sql->execute(array($arr));
        return $sql->fetchAll();
    }
    //metodo de ordenação
    public static function orderItem($tabela,$orderType,$idItem){
        if($orderType == 'up'){
            $infoAtual = Painel::select($tabela,'id=?',$idItem);
            $order_id = $infoAtual['order_id'];
            $itemBefor = MySql::connect()->prepare("SELECT * FROM `$tabela` WHERE order_id < $order_id ORDER BY order_id DESC LIMIT 1");
            $itemBefor->execute();
            if($itemBefor->rowCount()== 0)
                return;
            $itemBefor = $itemBefor->fetch();
            Painel::update(array('nome_tabela'=>$tabela,'id'=>$itemBefor['id'],'order_id'=>$infoAtual['order_id']));
            Painel::update(array('nome_tabela'=>$tabela,'id'=>$infoAtual['id'],'order_id'=>$itemBefor['order_id']));
        }else if($orderType == 'dow'){
            $infoAtual = Painel::select($tabela,'id=?',$idItem);
            $order_id = $infoAtual['order_id'];
            $itemBefor = MySql::connect()->prepare("SELECT * FROM `$tabela` WHERE order_id > $order_id ORDER BY order_id ASC LIMIT 1");
            $itemBefor->execute();
            if($itemBefor->rowCount()== 0)
                return;
            $itemBefor = $itemBefor->fetch();
            Painel::update(array('nome_tabela'=>$tabela,'id'=>$itemBefor['id'],'order_id'=>$infoAtual['order_id']));
            Painel::update(array('nome_tabela'=>$tabela,'id'=>$infoAtual['id'],'order_id'=>$itemBefor['order_id']));
        
        }
    }
    //gerar slug
    public static function generateSlug($str){
        $str =  mb_strtolower($str);
        $str =  preg_replace('/(á|â|ã|Á|Â|Ã)/','a',$str);
        $str =  preg_replace('/(ê|é|É|Ê)/','e',$str);
        $str =  preg_replace('/(í|Í|î|Î)/','i',$str);
        $str =  preg_replace('/(ú|Ú|Û|û)/','u',$str);
        $str =  preg_replace('/(ó|ô|õ|Ô|Ó|Õ)/','o',$str);
        $str =  preg_replace('/(_|\/|!|\?|#)/','',$str);
        $str =  preg_replace('/( )/','-',$str);
        $str =  preg_replace('/(ç|Ç)/','c',$str);
        $str =  preg_replace('/(-[-]{1,})/','-',$str);
        $str =  preg_replace('/(,)/','-',$str);
        $str =  mb_strtolower($str);
        return $str;
    }
    //selctSimples
    public static function selectSimples($tabela, $arr){
        $dados = MySql::connect()->prepare("SELECT * FROM `$tabela` WHERE  id = ?");
        $dados->execute(array($arr));
        return $dados = $dados->fetch();
    }
}
?>