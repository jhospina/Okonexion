<?php

namespace Appsthergo\API\Lang;

class Lang_EN {

    const MSJ_ERROR_CREATE_FORM = "Some fields entered is incorrect, please correct them:";
    const CREATE_USER_FORM_TITLE_SUCCESS = "¡ACTIVATE YOUR ACCOUNT!";
    const CREATE_USER_FORM_DESCRIPTION = "To register you must fill the form below";
    const CREATE_USER_FORM_TEXT_INPUT_NAME1 = "First Name";
    const CREATE_USER_FORM_ERROR_INPUT_NAME = "Invalid name";
    const CREATE_USER_FORM_TEXT_INPUT_NAME2 = "Second Name";
    const CREATE_USER_FORM_TEXT_INPUT_LASTNAME = "Lastname";
    const CREATE_USER_FORM_ERROR_INPUT_LASTNAME = "Invalid lastname";
    const CREATE_USER_FORM_TEXT_INPUT_EMAIL = "E-mail";
    const CREATE_USER_FORM_ERROR_INPUT_EMAIL = "Invalid E-mail";
    const CREATE_USER_FORM_TEXT_INPUT_PASSWORD = "Password";
    const CREATE_USER_FORM_ERROR_INPUT_PASSWORD = "Write a password of at least 6 characters long";
    const CREATE_USER_FORM_TEXT_INPUT_REPEAT_PASSWORD = "Repeat Password";
    const CREATE_USER_FORM_ERROR_INPUT_REPEAT_PASSWORD = "Passwords not match";
    const CREATE_USER_FORM_SUBMIT = "Create Account";
    const CREATE_USER_FORM_ERROR_EMAIL_EXISTS = "The e-mail entered already exists";
    const CREATE_USER_FORM_MSJ_SUCCESS = "Your account is almost ready! Please check your email <b>:email</b>, We have sent a message with a link, click on that link and we can confirm your email and activate your account. After that, your account will be activated and you can <a href=':link_login'>login</a>.";
    const LOGIN_FORM_INPUT_EMAIL = "E-mail";
    const LOGIN_FORM_INPUT_PASSWORD = "Password";
    const LOGIN_FORM_CHECK_REMEMBER = "Remember";
    const LOGIN_FORM_LINK_RECOVERY = "Forgotten password?";
    const LOGIN_FORM_SUBMIT = "Login";
    const LOGIN_FORM_DESCRIPTION_LABEL = "Login with your account";
    const LOGIN_FORM_MSJ_LOGOUT = "You have successfully logout!";
    const LOGIN_FORM_SEND_EMAIL_CONFIRMATION = "We've sent a message to your email <b>:email</b> with a link to activate your account. Check it please!";
    const LOGIN_FORM_MSJ_FAIL_CONFIRMATION = "Sorry, you can not access this account because it has not been activated. To activate it you must confirm your email <b>:email</b> by entering the link we send you to your email at the time of registration. <a href=':link'>Resend activation link?</a>";
    const LOGIN_FORM_MSJ_FAIL_LOGIN = "The email or password you entered is incorrect";
    const LOGIN_FORM_FAIL_RECOVERY_PASSWORD = "Sorry, there was an error while trying to reset your password, please try again later.";
    const LOGIN_FORM_MSJ_RECOVERY_PASSWORD = "<h1>YOUR PASSWORD HAS BEEN RESET!</h1><h2>Now you can log in with your new password</h2>";
    const LOGIN_FORM_MSJ_CONFIRMATION_MAIL = "<h1>¡THANK YOU!</h1><h2>Your account has been activated</h2>";
    const RECOVERY_FORM_TITLE = "Forgotten Password?";
    const RECOVERY_FORM_INPUT_EMAIL = "Enter your email:";
    const RECOVERY_FORM_ERROR_INPUT_EMAIL="The email entered is invalid";
    const RECOVERY_FORM_SUBMIT = "Send Request";
    const RECOVERY_FORM_MSJ_ACCOUNT_INACTIVE_TITLE = "ATTENTION!";
    const RECOVERY_FORM_MSJ_ACCOUNT_INACTIVE = "We can not help reset the password for this account associated with the email <b>:email</b> because this account has not been activated. To activate it you must confirm the email with which it was registered. If you are the owner of this account we invite you to check your emails. <a href=':link'>Resend activation link?</a>";
    const RECOVERY_FORM_MSJ_FAIL_EMAIL = "The email entered <b>:email</b> does not belong to any registered account.";
    const RECOVERY_FORM_MSJ_SEND_EMAIL_TITLE = "REQUEST SENT!";
    const RECOVERY_FORM_MSJ_SEND_EMAIL = "We've sent a message to your email <b>:email</b> with a link so that you can reset your password. Check it";
}
