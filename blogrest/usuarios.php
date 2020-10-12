<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Authorization, Access-Control-Allow-Methods, Access-Control-Allow-Headers, Allow, Access-Control-Allow-Origin");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS, HEAD");
header("Allow: GET, POST, PUT, DELETE, OPTIONS, HEAD");
require_once 'database.php';
require_once 'jwt.php';
if($_SERVER['REQUEST_METHOD']=="OPTIONS"){
    exit();
}

$header = apache_request_headers();
$jwt = trim($header['Authorization']);
switch (JWT::verify($jwt, CONFIG::SECRET_JWT)) {
    case 1:
        header("HTTP/1.1 401 Unauthorized");
        echo "El token no es válido";
        exit();
        break;
    case 2:
        header("HTTP/1.1 408 Request Timeout");
        echo "La sesión caduco";
        exit();
        break;
}

switch($_SERVER['REQUEST_METHOD']){
    case "GET":
        if(isset($_GET['user'])){
           /* $temas = new DataBase('temas');
            $where = array('idtema'=>$_GET['id']);
            $res = $temas->Read($where);*/
            $usuarios = new DataBase('usuarios');
            $where = array('user'=>$_GET['user']);
            $res = $usuarios->Read($where);
        }else{
            /*$temas = new DataBase('temas');
            $res = $temas->ReadAll();*/
            $usuarios = new DataBase('usuarios');
            $res = $usuarios->ReadAll();
        }
        header("HTTP/1.1 200 OK");
        echo json_encode($res);
    break;
    case "POST":
        if(isset($_POST['user']) && isset($_POST['nombre'])
        && isset($_POST['pass']) && isset($_POST['tipo'])){
            /*
            $temas = new DataBase('temas');
            $datos = array(
                'idtema'=>$_POST['id'],
                'nombre'=>$_POST['nombre']
            );
            */
            $usuarios = new DataBase('usuarios');
            $datos = array(
                'user'=>$_POST['user'],
                'pass'=>$_POST['pass'],
                'tipo'=>$_POST['tipo'],
                'nombre'=>$_POST['nombre']
            );
            try{
                /*
                $reg = $temas->create($datos);
                $res = array("result"=>"ok","msg"=>"Se guardo el tema", "id"=>$reg);
                */
                $reg = $usuarios->create($datos);
                $res = array("result"=>"ok","msg"=>"Se guardo el usuario", "id"=>$reg);
            }catch(PDOException $e){
                $res = array("result"=>"no","msg"=>$e->getMessage());
            }
            /********************** */
            /*$con=null;
            try{
                $con = new PDO("mysql:host=LOCALHOST;dbname=blogresp", "root", "");
                $con->setAttribute(PDO::ATTR_ERRMODE, 
                    PDO::ERRMODE_EXCEPTION);
            }catch(PDOException $e){
                exit($e->getMessage());
            }
            $stm = $con->preppare("INSERT INTO temas VALUES(:id, :nombre)");
            $stm->bindValue(":id",$_POST['id']);
            $stm->bindValue(":nombre",$_POST['nombre']);
            $stm->execute();
            $res = array("result"=>"ok","msg"=>"Se guardo el tema", "id"=>$reg);*/
            /************************** */
        
        }else{
            $res = array("result"=>"no","msg"=>"Faltan datos");
        }
        header("HTTP/1.1 200 OK");
        echo json_encode($res);
    break;
    case "PUT":
        if(isset($_GET['user']) && isset($_GET['nombre'])
        && isset($_GET['pass']) && isset($_GET['tipo'])){
            /*
            $temas = new DataBase('temas');
            $where = array('idtema'=>$_GET['id']);
            $datos = array('nombre'=>$_GET['nombre']);
            $reg = $temas->update($datos,$where);

            $res = array("result"=>"ok","msg"=>"Se guardo el tema", "num"=>$reg);
        */
        $usuarios = new DataBase('usuarios');
            $where = array('user'=>$_GET['user']);
            $datos = array(
                'user'=>$_GET['user'],
                'pass'=>$_GET['pass'],
                'tipo'=>$_GET['tipo'],
                'nombre'=>$_GET['nombre']
            );
            $reg = $usuarios->update($datos,$where);

            $res = array("result"=>"ok","msg"=>"Se guardo el usuario", "num"=>$reg);
        }else{
            $res = array("result"=>"no","msg"=>"Faltan datos");
        }
        echo json_encode($res);
    break;
    case "DELETE":
        if(isset($_GET['user'])){
            
            /*$temas = new DataBase('temas');
            $where = array('idtema'=>$_GET['id']);
            $reg = $temas->delete($where);
            $res = array("result"=>"ok","msg"=>"Se elimino el tema", "num"=>$reg);*/
            $usuarios = new DataBase('usuarios');
            $where = array('user'=>$_GET['user']);
            $reg = $usuarios->delete($where);
            $res = array("result"=>"ok","msg"=>"Se elimino el usuario", "num"=>$reg);
        
        }else{
            $res = array("result"=>"no","msg"=>"Faltan datos");
        }
        echo json_encode($res);
    break;
    default:
        header("HTTP/1.1 401 Bad Request");
}