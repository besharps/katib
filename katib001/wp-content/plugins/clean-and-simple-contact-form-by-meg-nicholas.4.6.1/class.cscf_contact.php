<?php

/*
 * class for holding and validating data captured from the contact form
*/

class cscf_Contact {
	var $Name;
	var $Email;
	var $ConfirmEmail;
	var $Message;
	var $EmailToSender;
	var $ErrorMessage;
	var $RecaptchaPublicKey;
	var $RecaptchaPrivateKey;
	var $Errors;
	var $PostID;
	var $IsSpam;

	function __construct() {
		$this->Errors = array();

		if ( cscf_PluginSettings::UseRecaptcha() ) {
			$this->RecaptchaPublicKey  = cscf_PluginSettings::PublicKey();
			$this->RecaptchaPrivateKey = cscf_PluginSettings::PrivateKey();
		}

		if ( $_SERVER['REQUEST_METHOD'] == 'POST' && isset( $_POST['cscf'] ) ) {
			$cscf        = $_POST['cscf'];
			$this->Name  = filter_var( $cscf['name'], FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES );
			$this->Email = filter_var( $cscf['email'], FILTER_SANITIZE_EMAIL );

			if ( isset( $cscf['confirm-email'] ) ) {
				$this->ConfirmEmail = filter_var( $cscf['confirm-email'], FILTER_SANITIZE_EMAIL );
			}

			$this->EmailToSender = isset( $cscf['email-sender'] );

			$this->Message = filter_var( $cscf['message'], FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES );
			if ( isset( $_POST['post-id'] ) ) {
				$this->PostID = $_POST['post-id'];
			}
			unset( $_POST['cscf'] );
		}

		$this->IsSpam = false;
	}

	public
	function IsValid() {
		$this->Errors = array();

		if ( $_SERVER['REQUEST_METHOD'] != 'POST' ) {
			return false;
		}

		//check nonce

		if ( ! wp_verify_nonce( $_POST['cscf_nonce'], 'cscf_contact' ) ) {
			return false;
		}

		// email and confirm email are the same
		if ( cscf_PluginSettings::ConfirmEmail() ) {
			if ( $this->Email != $this->ConfirmEmail ) {
				$this->Errors['confirm-email'] = __( 'Sorry the email addresses do not match.', 'clean-and-simple-contact-form-by-meg-nicholas' );
			}
		}

		//email

		if ( strlen( $this->Email ) == 0 ) {
			$this->Errors['email'] = __( 'Please give your email address.', 'clean-and-simple-contact-form-by-meg-nicholas' );
		}

		//confirm email
		if ( cscf_PluginSettings::ConfirmEmail() ) {
			if ( strlen( $this->ConfirmEmail ) == 0 ) {
				$this->Errors['confirm-email'] = __( 'Please confirm your email address.', 'clean-and-simple-contact-form-by-meg-nicholas' );
			}
		}

		//name

		if ( strlen( $this->Name ) == 0 ) {
			$this->Errors['name'] = __( 'Please give your name.', 'clean-and-simple-contact-form-by-meg-nicholas' );
		}

		//message

		if ( strlen( $this->Message ) == 0 ) {
			$this->Errors['message'] = __( 'Please enter a message.', 'clean-and-simple-contact-form-by-meg-nicholas' );
		}

		//email invalid address

		if ( strlen( $this->Email ) > 0 && ! filter_var( $this->Email, FILTER_VALIDATE_EMAIL ) ) {
			$this->Errors['email'] = __( 'Please enter a valid email address.', 'clean-and-simple-contact-form-by-meg-nicholas' );
		}

		//check recaptcha but only if we have keys

		if ( $this->RecaptchaPublicKey <> '' && $this->RecaptchaPrivateKey <> '' ) {
			$resp = csf_RecaptchaV2::VerifyResponse( $_SERVER["REMOTE_ADDR"], $this->RecaptchaPrivateKey, $_POST["g-recaptcha-response"] );

			if ( ! $resp->success ) {
//	            $this->Errors['recaptcha'] = __('Sorry the code wasn\'t entered correctly please try again.', 'clean-and-simple-contact-form-by-meg-nicholas');
				$this->Errors['recaptcha'] = __( 'Please solve the recaptcha to continue.', 'clean-and-simple-contact-form-by-meg-nicholas' );
			}
		}

		return count( $this->Errors ) == 0;
	}

	public
	function SendMail() {
		apply_filters( 'cscf_spamfilter', $this );

		if ( $this->IsSpam === true ) {
			return true;
		}

		$filters = new cscf_Filters;

		if ( cscf_PluginSettings::OverrideFrom() & cscf_PluginSettings::FromEmail() != "" ) {
			$filters->fromEmail = cscf_PluginSettings::FromEmail();
		} else {
			$filters->fromEmail = $this->Email;
		}

		$filters->fromName = $this->Name;

		//add filters
		$filters->add( 'wp_mail_from' );
		$filters->add( 'wp_mail_from_name' );

		//headers
		$header = "Reply-To: " . $this->Email . "\r\n";

		//message
		$message = __( 'From: ', 'clean-and-simple-contact-form-by-meg-nicholas' ) . $this->Name . "\n\n";
		$message .= __( 'Email: ', 'clean-and-simple-contact-form-by-meg-nicholas' ) . $this->Email . "\n\n";
		$message .= __( 'Page URL: ', 'clean-and-simple-contact-form-by-meg-nicholas' ) . get_permalink( $this->PostID ) . "\n\n";
		$message .= __( 'Message:', 'clean-and-simple-contact-form-by-meg-nicholas' ) . "\n\n" . $this->Message;

		$result = ( wp_mail( cscf_PluginSettings::RecipientEmails(), cscf_PluginSettings::Subject(), stripslashes( $message ), $header ) );

		//remove filters (play nice)
		$filters->remove( 'wp_mail_from' );
		$filters->remove( 'wp_mail_from_name' );

		//send an email to the form-filler
		if ( $this->EmailToSender ) {
			$recipients = cscf_PluginSettings::RecipientEmails();

			if ( cscf_PluginSettings::OverrideFrom() & cscf_PluginSettings::FromEmail() != "" ) {
				$filters->fromEmail = cscf_PluginSettings::FromEmail();
			} else {
				$filters->fromEmail = $recipients[0];
			}

			$filters->fromName = get_bloginfo( 'name' );

			//add filters
			$filters->add( 'wp_mail_from' );
			$filters->add( 'wp_mail_from_name' );

			$header  = "";
			$message = cscf_PluginSettings::SentMessageBody() . "\n\n";
			$message .= __( "Here is a copy of your message :", "clean-and-simple-contact-form-by-meg-nicholas" ) . "\n\n";
			$message .= $this->Message;

			$result = ( wp_mail( $this->Email, cscf_PluginSettings::Subject(), stripslashes( $message ), $header ) );

			//remove filters (play nice)
			$filters->remove( 'wp_mail_from' );
			$filters->remove( 'wp_mail_from_name' );
		}

		return $result;
	}
}
