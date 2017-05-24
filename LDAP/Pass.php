<?php
ini_set('display_errors',1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if (!isset($_POST["humano"])){
	$_SESSION["ERROR"] = "Completa el captcha";
	header('Location: index.php');
}elseif (isset($_POST["robot"])){
	$_SESSION["ERROR"] = "Eres un robot!";
	header('Location: index.php');
}

$username =  $_POST["username"];
$name = $_POST["user"];
$newpass = $_POST["new_password"];
$oldpass = $_POST["old_pass"];
$dn = "uid=".$name.",dc=daw2,dc=net";

if(isset($username) and isset($newpass) and isset($oldpass)) {

    $ldapconn = ldap_connect("localhost");
    ldap_set_option($ldapconn, LDAP_OPT_PROTOCOL_VERSION, 3);
    ldap_set_option($ldapconn, LDAP_OPT_REFERRALS, 0);

    $ldapbind = ldap_bind($ldapconn,$username,$oldpass);

    if($ldapbind) {
      echo "<p>Change password ";

      if(ldap_mod_replace($ldapconn,$username,
        array('userpassword' => "{MD5}".base64_encode(pack("H*",md5($newpass)))))) {
        header('Location: index.php');
      }
      else {
        $_SESSION["ERROR"] = "No se ha podido cambiar la contraseña";
        header('Location: index.php');
      }
      print "</p>\n";
    }
}



?>
