<?php
class Usuario{
    public $codigo, $nome, $email, $senha;
    
    function __construct($codigo, $nome, $email, $senha) {
        $this->codigo = $codigo;
        $this->nome = $nome;
        $this->senha = $senha;
        $this->email = $email;
    }
    
    function validaUsuarioSenha($email, $senha){
        if($email == $this->email && $senha == $this->senha){
            return TRUE;
        } else {
            return FALSE;
        }
    }
    
    function getCod() {
        return $this->codigo;
    }
    
    function getNome() {
        return $this->nome;
    }
}
?>