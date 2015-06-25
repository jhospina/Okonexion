<?php

namespace Appsthergo\API;

require 'Config.php';
require 'Lang.php';
require 'Util.php';

use Appsthergo\API\Config as Config;
use Appsthergo\API\Lang as Lang;
use Appsthergo\API\Util as Util;

class Html extends Lang {

    public function formCreateUserHeader($title) {
        $output = " <header class='page-header'>";
        $output.="<h1 class='blog-title'>" . $title . "</h1>";
        $output.=" </header>";
        return $output;
    }

    //********************************************************************
    //FORM CREATE USER****************************************************
    //********************************************************************


    public function formCreateUser() {

        $output = "";
        $output.="<div id='aptg-form-create-user' class='contact-form-wrapper' style='margin-bottom:20px;'>";
        $output.="<h2>" . parent::printText(parent::CREATE_USER_FORM_DESCRIPTION) . "</h2>";
        $output.="<form id='form_cuenta' method='post' action='" . Config::URL_POST_CREATE_USER . "'>";
        $output.=" <div class='grids'>";
        $output.="    <div class='msj-error' id='msj-error'>" . parent::printText(parent::MSJ_ERROR_CREATE_FORM) . "</br><ul style='margin: 5px;' id='error-description'></ul></div>";
        $output.="    <p class='grid-6'>";
        $output.="        <label for='nombre1'>" . parent::printText(parent::CREATE_USER_FORM_TEXT_INPUT_NAME1) . "</label>";
        $output.="        <input type='text' name='nombre1' id='nombre1' value=''>";
        $output.="    </p>";
        $output.="     <p class='grid-6'>";
        $output.="         <label for='nombre2'>" . parent::printText(parent::CREATE_USER_FORM_TEXT_INPUT_NAME2) . "</label>";
        $output.="        <input type='text' name='nombre2' id='nombre2' value=''>";
        $output.="     </p>";
        $output.="     <p class='grid-6'>";
        $output.="         <label for='apellidos'>" . parent::printText(parent::CREATE_USER_FORM_TEXT_INPUT_LASTNAME) . "</label>";
        $output.="         <input type='text' name='apellidos' id='apellidos' value=''>";
        $output.="     </p>";
        $output.="       <p class='grid-6'>";
        $output.="          <label for='email'>" . parent::printText(parent::CREATE_USER_FORM_TEXT_INPUT_EMAIL) . "</label>";
        $output.="          <input type='text' name='email' id='email' value=''>";
        $output.="      </p>";
        $output.="       <p class='grid-6'>";
        $output.="          <label for='password'>" . parent::printText(parent::CREATE_USER_FORM_TEXT_INPUT_PASSWORD) . "</label>";
        $output.="           <input type='password' name='password' id='password' value=''>";
        $output.="       </p>";
        $output.="       <p class='grid-6'>";
        $output.="           <label for='password_rep'>" . parent::printText(parent::CREATE_USER_FORM_TEXT_INPUT_REPEAT_PASSWORD) . "</label>";
        $output.="           <input type='password' name='password_rep' id='password_rep' value=''>";
        $output.="       </p>";
        $output.="       <p class='buttons'>";
        $output.="           <input style='color: #fff;font-weight: bold;text-align: center;text-transform: uppercase; line-height: 1;-moz-transition: all 0.35s;-o-transition: all 0.35s;-webkit-transition: all 0.35s;transition: all 0.35s;background-color: #00b7e5;' type='button' id='submitted' class='contact-form-button' name='submitted' value='" . parent::printText(parent::CREATE_USER_FORM_SUBMIT) . "'>";
        $output.="        </p>";
        $output.="    </div>";
        $output.="      <input type='hidden' name='url' value='";
        $output.=($_SERVER['HTTPS']) ? 'https://' : 'http://';
        $output.=$_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'] . "'/>";
        $output.="  </form>";
        $output.=" </div>";


        return $output;
    }

    public function formCreateUser_script() {

        $output = "<script>";
        $output.="    jQuery('#submitted').click(function () {";
        $output.="       var val = true;";
        $output.="      var nombre1 = jQuery('#nombre1').val();";
        $output.="      var nombre2 = jQuery('#nombre2').val();";
        $output.="      var apellidos = jQuery('#apellidos').val();";
        $output.="     var email = jQuery('#email').val();";
        $output.="      var contra = jQuery('#password').val();";
        $output.="      var contra_rep = jQuery('#password_rep').val();";
        $output.="     jQuery('#msj-error').fadeOut();";
        $output.="     jQuery('#error-description').html('');";
        $output.="     if (!validarNombre(nombre1)) {";
        $output.="          val = false;";
        $output.="          jQuery('#error-description').append('<li>" . parent::printText(parent::CREATE_USER_FORM_ERROR_INPUT_NAME) . "</li>');";
        $output.="      }";
        $output.="     if (!validarNombre(apellidos)) {";
        $output.="         val = false;";
        $output.="         jQuery('#error-description').append('<li>" . parent::printText(parent::CREATE_USER_FORM_ERROR_INPUT_LASTNAME) . "</li>');";
        $output.="      }";
        $output.="       if (!validarEmail(email)) {";
        $output.="           val = false;";
        $output.="           jQuery('#error-description').append('<li>" . parent::printText(parent::CREATE_USER_FORM_ERROR_INPUT_EMAIL) . "</li>');";
        $output.="      }";
        $output.="     if (contra.length < 6) {";
        $output.="          val = false;";
        $output.="          jQuery('#error-description').append('<li>" . parent::printText(parent::CREATE_USER_FORM_ERROR_INPUT_PASSWORD) . "</li>');";
        $output.="      }";
        $output.="        if (contra != contra_rep) {";
        $output.="          val = false;";
        $output.="          jQuery('#error-description').append('<li>" . parent::printText(parent::CREATE_USER_FORM_ERROR_INPUT_REPEAT_PASSWORD) . "</li>');";
        $output.="       }";
        $output.="       if (val)";
        $output.="           jQuery('#form_cuenta').submit();";
        $output.="        else";
        $output.="            jQuery('#msj-error').slideToggle();";
        $output.="      });";
        $output.="  ";
        $output.="   function validarNombre(name)";
        $output.="     {";
        $output.="        if (name.length < 3) {";
        $output.="           return (false);";
        $output.="       }";
        $output.="       var checkOK = 'ABCDEFGHIJKLMNÑOPQRSTUVWXYZ1234567890.ÁÉÍÓÚ' + 'abcdefghijklmnñopqrstuvwxyzáéíóú ';";
        $output.="       var checkStr = name;";
        $output.="       var allValid = true;";
        $output.="       for (i = 0; i < checkStr.length; i++) {";
        $output.="           ch = checkStr.charAt(i);";
        $output.="          for (j = 0; j < checkOK.length; j++)";
        $output.="            if (ch == checkOK.charAt(j))";
        $output.="                break;";
        $output.="       if (j == checkOK.length) {";
        $output.="           allValid = false; break;";
        $output.="       }";
        $output.="   }";
        $output.="   if (!allValid) {";
        $output.="        return (false);";
        $output.="    }";
        $output.="    return (true);";
        $output.="    }";
        $output.="   function validarEmail(email) {";
        $output.="       expr = /^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;";
        $output.="        if (expr.test(email))";
        $output.="          return true;";
        $output.="      else";
        $output.="          return false;";
        $output.="    } ";
        $output.=" </script>";

        return $output;
    }

    public function msj_error_email_exists() {
        $output = " <div style='padding: 10px;margin-bottom: 20px;font-size: 12pt;color:red;border:1px red dashed;'><p>" . parent::printText(parent::CREATE_USER_FORM_ERROR_EMAIL_EXISTS) . "</p></div>";
        return $output;
    }

    public function msj_success_create_user($email, $link_login) {
        $output = "<div style='padding: 20px;margin-bottom: 20px;font-size: 15pt;'><p>" . parent::printText(parent::CREATE_USER_FORM_MSJ_SUCCESS, array("email" => $email, "link_login" => $link_login)) . "</p></div>";
        return $output;
    }

    //********************************************************************
    //FORM LOGIN**********************************************************
    //********************************************************************

    public function formLogin() {
        $output = " <div id='aptg-login' class='contact-form-wrapper' style='margin-bottom:150px;'>";
        $output.="  <form id='form_cuenta' method='post' action='" . Config::URL_POST_LOGIN . "'>";

        $output.="    <h2>" . parent::printText(parent::LOGIN_FORM_DESCRIPTION_LABEL) . "</h2>";
        $output.="  <div class='grids' style='max-width: 500px;margin: auto;'>";
        $output.="     <p class='grid-12'>";
        $output.="        <label for='email'>" . parent::printText(parent::LOGIN_FORM_INPUT_EMAIL) . "</label>";
        $output.="       <input type='text' name='email' id='email' value='";
        $output.=(isset($_GET['email'])) ? $_GET['email'] : '';
        $output.="'></p>";
        $output.="    <p class='grid-12'>";
        $output.="        <label for='contrasena'>" . parent::printText(parent::LOGIN_FORM_INPUT_PASSWORD) . "</label>";
        $output.="        <input type='password' name='contrasena' id='contrasena' value=''>";
        $output.="    </p>";
        $output.="    <p class='grid-6'>";
        $output.="       <input type='checkbox' name='recordarme'/> " . parent::printText(parent::LOGIN_FORM_CHECK_REMEMBER);
        $output.="     </p>";
        $output.="     <p class='grid-6' style='text-align: right;'>";
        $output.="          <a href='" . Util::UrlFiltrate(Util::getUrlCurrent()) . "?stage=recovery'>" . parent::printText(parent::LOGIN_FORM_LINK_RECOVERY) . "</a>";
        $output.="      </p>";
        $output.="       <input type='hidden' name='url' value='" . Util::UrlFiltrate(Util::getUrlCurrent()) . "'/>";
        $output.=" <p class='buttons'>";
        $output.="    <input type='submit' id='submitted' class='contact-form-button' name='submitted' value='" . parent::printText(parent::LOGIN_FORM_SUBMIT) . "'>";
        $output.="  </p>";
        $output.="    </div>";
        $output.="   </form>";
        $output.="     </div>";

        return $output;
    }

    public function msj_logout() {
        $output = " <div style='color: white;
                 padding: 8px;
                 border: 1px rgb(0, 94, 0) solid;
                 background: rgba(0, 128, 0, 0.8);
                 -webkit-border-radius: 5px;
                 -moz-border-radius: 5px;
                 border-radius: 5px;'>
                <p style='font-size: 15pt;'>" . parent::printText(parent::LOGIN_FORM_MSJ_LOGOUT) . "</p>
            </div>";
        return $output;
    }

    public function msj_send_email_confirmation() {
        $output = "<div style='color: white;
                 padding: 8px;
                 border: 1px rgb(0, 94, 0) solid;
                 background: rgba(0, 128, 0, 0.8);
                 -webkit-border-radius: 5px;
                 -moz-border-radius: 5px;
                 border-radius: 5px;'>
                <p style='font-size: 15pt;'>" . parent::printText(parent::LOGIN_FORM_SEND_EMAIL_CONFIRMATION, array("email" => (isset($_GET["email"])) ? $_GET["email"] : "")) . "</div>";
        return $output;
    }

    public function msj_fail_confirmation() {

        $text = parent::printText(parent::LOGIN_FORM_MSJ_FAIL_CONFIRMATION, array(
                    "email" => (isset($_GET["email"])) ? $_GET["email"] : "",
                    "link" => str_replace(":id_user", (isset($_GET["user"])) ? $_GET["user"] : 0, Config::URL_GET_FORWARD_ACTIVATION_ACCOUNT)));

        $output = "<div style='color: red;padding: 10px;border: 1px red dashed;'>";
        $output.="<p style='font-size: 15pt;'>" . $text . "</p></div>";

        return $output;
    }

    public function msj_login_fail() {
        $output = " <div style='color: red;padding: 10px;border: 1px red dashed;'><p style='font-size: 15pt;'>" . parent::printText(parent::LOGIN_FORM_MSJ_FAIL_LOGIN) . "</p></div>";
        return $output;
    }

    public function msj_fail_password_recovery() {
        $output = "<div style='color: red;padding: 10px;border: 1px red dashed;'>
                <p style='font-size: 15pt;'>" . parent::printText(parent::LOGIN_FORM_FAIL_RECOVERY_PASSWORD) . "</p>
            </div>";
        return $output;
    }

    public function msj_recovery_password() {
        $output = "<header class='page-header'>" . parent::printText(parent::LOGIN_FORM_MSJ_RECOVERY_PASSWORD) . "</header>";
        return $output;
    }

    public function msj_confirmation_email() {
        $output = "<header class='page-header'>" . parent::printText(parent::LOGIN_FORM_MSJ_CONFIRMATION_MAIL) . "</header>";
        return $output;
    }

    //********************************************************************
    //FORM RECOVERY PASSWORD**************************************************
    //********************************************************************

    public function formRecoveryPassword($lang) {
        $output = "<div id='aptg-form-recovery' class='contact-form-wrapper'>";
        $output.="<form id='form' method='post' action='" . Config::URL_POST_RECOVERY_PASSWORD . "?lang=" . $lang . "'>";
        $output.="    <h2>" . parent::printText(parent::RECOVERY_FORM_TITLE) . "</h2>";
        $output.="    <div class='msj-error' id='msj-error' style='display:none;'></div>";
        $output.="    <div class='grids' style='max-width: 500px;margin: auto;'>";
        $output.="<p class='grid-12'>";
        $output.="    <label for='email'>" . parent::printText(parent::RECOVERY_FORM_INPUT_EMAIL) . "</label>";
        $output.="   <input type='text' name='email' id='email' value=''>";
        $output.="<input type='hidden' name='url' value='" . Util::getUrlCurrent() . "'/>";
        $output.=" </p>";
        $output.=" <p class='buttons'>";
        $output.="     <input style='color: #fff;font-weight: bold;text-align: center;text-transform: uppercase; line-height: 1;-moz-transition: all 0.35s;-o-transition: all 0.35s;-webkit-transition: all 0.35s;transition: all 0.35s;background-color: #00b7e5;' type='button' id='submitted' class='contact-form-button' name='submitted' value='" . parent::printText(parent::RECOVERY_FORM_SUBMIT) . "'>";
        $output.=" </p>";
        $output.=" </div>";
        $output.="     </form>";
        $output.="  </div>";

        return $output;
    }

    public function formRecoveryPassword_script() {
        $output = "<script>";
        $output.=" jQuery('#submitted').click(function () {";
        $output.= "  var email = jQuery('#email').val();";
        $output.= "   var val = true;";
        $output.= " if (!validarEmail(email)) {";
        $output.= "      jQuery('#msj-error').html('El correo electrónico ingresado es invalido');";
        $output.= "      jQuery('#msj-error').slideToggle();";
        $output.= "      val = false;";
        $output.= "  }";
        $output.= "   if (val)";
        $output.= "       jQuery('#form').submit();";
        $output.= "  });";
        $output.= "  function validarEmail(email) {";
        $output.= "      expr = /^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;";
        $output.= "      if (expr.test(email))";
        $output.= "          return true;";
        $output.= "      else";
        $output.= "          return false;";
        $output.= "   }";
        $output.= " </script>";

        return $output;
    }

    public function msj_rp_response_account_inactive() {

        $text_description = parent::printText(parent::RECOVERY_FORM_MSJ_ACCOUNT_INACTIVE, array(
                    "email" => (isset($_GET["email"])) ? $_GET["email"] : "",
                    "link" => str_replace(":id_user", (isset($_GET["user"])) ? $_GET["user"] : 0, Config::URL_GET_FORWARD_ACTIVATION_ACCOUNT)));

        $output = "<div style='color: orangered;padding: 10px;border: 1px orangered dashed;'>";
        $output.="<div style='text-align: center;font-size: 17pt;'><b>" . parent::printText(parent::RECOVERY_FORM_MSJ_ACCOUNT_INACTIVE_TITLE) . "</b></div>";
        $output.="<p>" . $text_description . "</p></div>";
        return $output;
    }

    public function msj_rp_response_fail_email() {
        $output = "<div style='color: red;padding: 10px;border: 1px red dashed;'><p>" . parent::printText(parent::RECOVERY_FORM_MSJ_FAIL_EMAIL, array(
                    "email" => (isset($_GET["email"])) ? $_GET["email"] : "")) . "</p></div>";
        return $output;
    }

    public function msj_rp_response_send_email() {
        $output = " <header class='page-header'>";
        $output.="<h1 class='blog-title'>" . parent::printText(parent::RECOVERY_FORM_MSJ_SEND_EMAIL_TITLE) . "</h1>";
        $output.=" </header>";
        $output.=" <div style='padding: 20px;margin-bottom: 20px;font-size: 15pt;'>";
        $output.="  <p>" . parent::printText(parent::RECOVERY_FORM_MSJ_SEND_EMAIL, array(
                    "email" => (isset($_GET["email"])) ? $_GET["email"] : "")) . "</p>";
        $output.="  </div>";
        return $output;
    }

}
