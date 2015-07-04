<?php

namespace Appsthergo\API\Lang;

class Lang_ES {

    const MSJ_ERROR_CREATE_FORM = "Algunos campos ingresados están incorrectos por favor corrígelos:";
    const CREATE_USER_FORM_TITLE_SUCCESS = "¡ACTIVA TU CUENTA!";
    const CREATE_USER_FORM_DESCRIPTION = "Para crear una cuenta debes llenar el siguiente formulario";
    const CREATE_USER_FORM_TEXT_INPUT_NAME1 = "Primer Nombre";
    const CREATE_USER_FORM_ERROR_INPUT_NAME = "Nombre invalido";
    const CREATE_USER_FORM_TEXT_INPUT_NAME2 = "Segundo Nombre";
    const CREATE_USER_FORM_TEXT_INPUT_LASTNAME = "Apellidos";
    const CREATE_USER_FORM_ERROR_INPUT_LASTNAME = "Apellidos invalido";
    const CREATE_USER_FORM_TEXT_INPUT_EMAIL = "Correo Electrónico";
    const CREATE_USER_FORM_ERROR_INPUT_EMAIL = "Correo Electrónico invalido";
    const CREATE_USER_FORM_TEXT_INPUT_PASSWORD = "Contraseña";
    const CREATE_USER_FORM_ERROR_INPUT_PASSWORD = "Escribe una contraseña de al menos 6 caracteres";
    const CREATE_USER_FORM_TEXT_INPUT_REPEAT_PASSWORD = "Repetir Contraseña";
    const CREATE_USER_FORM_ERROR_INPUT_REPEAT_PASSWORD = "Las contraseñas deben coincidir";
    const CREATE_USER_FORM_SUBMIT = "Crear Cuenta";
    const CREATE_USER_FORM_ERROR_EMAIL_EXISTS = "El correo eletrónico ingresado ya existe";
    const CREATE_USER_FORM_MSJ_SUCCESS = "¡Tu cuenta esta casi lista! Por favor revisa tu correo electrónico <b>:email</b>, hemos enviado un mensaje con un enlace, haz clic en ese enlace y así podremos confirmar tu correo electrónico y activar tu cuenta. Luego de esto, tu cuenta quedara activada y podrás iniciar sesión en <a href=':link_login'>ingresar</a>.";
    const LOGIN_FORM_INPUT_EMAIL = "Correo electrónico";
    const LOGIN_FORM_INPUT_PASSWORD = "Constraseña";
    const LOGIN_FORM_CHECK_REMEMBER = "Recordarme";
    const LOGIN_FORM_LINK_RECOVERY = "¿Olvidastes tu contraseña?";
    const LOGIN_FORM_SUBMIT = "Iniciar sesión";
    const LOGIN_FORM_DESCRIPTION_LABEL = "Inicia sesión con tu cuenta";
    const LOGIN_FORM_MSJ_LOGOUT = "¡Ha cerrado sesión con exito!";
    const LOGIN_FORM_SEND_EMAIL_CONFIRMATION = "Hemos enviado un mensaje a tu correo electrónico <b>:email</b> con un enlace para activar tu cuenta. ¡Revisalo por favor!";
    const LOGIN_FORM_MSJ_FAIL_CONFIRMATION = "Lo sentimos, no puedes ingresar a esta cuenta porque no ha sido activada. Para activarla debes confirmar tu correo electrónico ingresando en el enlace que te enviamos a tu correo <b>:email</b> al momento del registro. <a href=':link'>¿Reenviar enlace de activación?</a>";
    const LOGIN_FORM_MSJ_FAIL_LOGIN = "El correo electrónico o la contraseña ingresada son incorrectas";
    const LOGIN_FORM_FAIL_RECOVERY_PASSWORD = "Lo sentimos, hubo un error al tratar de reestablecer tu contraseña, inténtalo de nuevo más tarde.";
    const LOGIN_FORM_MSJ_RECOVERY_PASSWORD = "<h1>¡TU CONTRASEÑA HA SIDO RESTRABLECIDA!</h1><h2>Ya puedes iniciar sesión con tu nueva contraseña</h2>";
    const LOGIN_FORM_MSJ_CONFIRMATION_MAIL = "<h1>¡MUCHAS GRACIAS!</h1><h2>Tu cuenta ha sido activada</h2>";
    const RECOVERY_FORM_TITLE = "¿Olvidastes tu contraseña?";
    const RECOVERY_FORM_INPUT_EMAIL = "Ingresa tu correo electrónico:";
    const RECOVERY_FORM_ERROR_INPUT_EMAIL="El correo electrónico ingresado es invalido";
    const RECOVERY_FORM_SUBMIT = "Enviar Solicitud"; 
    const RECOVERY_FORM_MSJ_ACCOUNT_INACTIVE_TITLE = "¡ATENCIÓN!";
    const RECOVERY_FORM_MSJ_ACCOUNT_INACTIVE = "No podemos ayudarte a reestablecer la contraseña de esta cuenta asociada al correo electrónico <b>:email</b> porque esta cuenta no ha sido activada. Para activarla se debe confirmar el correo electrónico con el que fue registrado. Si eres el dueño de esta cuenta te invitamos a revisar tu correo. <a href=':link'>¿Reenviar enlace de activación?";
    const RECOVERY_FORM_MSJ_FAIL_EMAIL = "El correo electrónico <b>:email</b> ingresado no pertenece a ninguna cuenta registrada.";
    const RECOVERY_FORM_MSJ_SEND_EMAIL_TITLE = "¡SOLICITUD ENVIADA!";
    const RECOVERY_FORM_MSJ_SEND_EMAIL = "Hemos enviado un mensaje a tu correo electrónico <b>:email</b> con un enlace para que puedas reestablecer tu contraseña. ¡Revísalo cuanto antes!";

}
