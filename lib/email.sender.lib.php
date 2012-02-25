<?
include_once('lib/email.lib.php');
include_once('lib/filter.lib.php');
require_once('model/account.model.php');
require_once('sdk/amazonsdk/sdk.class.php');

interface IMailerSystem {

	public function send($subject,$template, $to, $reply_email, $from_user);

}

class localMailer extends PrepareSending implements IMailerSystem {

	public function send($subject,$template, $to, $reply_email, $from) {
		$this->setParams($subject,$template, $reply_email, $from);
		$this->preSend($to);
		$param['from'] = array(
			'email' => $this->reply_email,
			'user' =>$this->from,
		);
		foreach($this->messages as $to=>$body) {
			$this->messages[$to] = common_sendMail($subject, $body, $to, $param);
		}
	}
}

class PrepareSending {

	const UNSUBSCRIBE = 'unsubscribe';

	public $messages = array();

	public $subject;

	public $reply_email;

	public $from;

	public $template;

	/*
	 *   Replace or not replace_list value by users fields
	 */
	public $replace = false;

	public $replace_list = array();

	public function getPersonMessage($user) {
		if($this->replace) {
			$replace = array();
			foreach($this->replace_list as $key=>$item) {
				if($item ===  self::UNSUBSCRIBE)
					$replace[$key] = $this->unsubscribeUrl($user);
				else $replace[$key] = (isset($user[$item])) ? $user[$item] : '';
			}
		} else $replace = $this->replace_list;
		$message = str_replace(array_keys($replace), array_values($replace),$this->template);
		return $message;
	}

	public function setParams($subject,$template, $reply_email, $from) {
		$this->subject = $subject;
		$this->from = $from;
		$this->reply_email = $reply_email;
		$this->template = $template;
	}

	public function preSend(array $accounts) {
		foreach($accounts as $user) {
			if(!self::validateUser($user)) continue;
			$this->messages[$user['email']] = $this->getEmailBody($user);
		}
	}

	public function preSendExt(array $accounts) {
		foreach($accounts as $user) {
			if(!self::validateUser($user)) continue;
			$this->messages[] = array(
				'email' => $user['email'],
				'id' => $user['id'],
				'body' => $this->getEmailBody($user),
				'emailErrors' => $user['emailErrors'],
			);
		}
	}

	public function getEmailBody($user) {
		if(count($this->replace_list)) {
			$body = $this->getPersonMessage($user);
		} else $body = $this->template;
		return $body;
	}


	public static function validateUser($user){
		return ($user['email'] && $user['subscribe'] &&
			($user['emailErrors'] < EMAIL_ERRORS_LIMIT));
	}

	public function unsubscribeUrl($account) {
		$code = AccountModel::getUnsubscribeCode($account['email'], $account['id']);
		$url = HTTP_HOST.'/unsubscribe?email='.$account['email'].'&code='.$code;
		return $url;
	}

	public function setReplaceList($replace_list = null) {
		$this->replace_list = $replace_list;
	}

	public function doReplace($yes = true) {
		$this->replace = $yes;
	}

	public function createReplaceList($template) {
		if(count(preg_match_all('/%%[a-zA-Z0-9]+%%/', $template, $matches))) {
			foreach($matches[0] as $value) {
				preg_match('/[a-zA-Z0-9]+/', $value, $name);
				$replace_list[$value] = strtolower($name[0]);
			}
		} else $replace_list = array();
		return $replace_list;
	}

}

class AmazonSesMailer extends PrepareSending implements IMailerSystem {

	public $Amazon;

	public $response = array();

	public function __construct() {
		$this->Amazon = new AmazonSES();
	}

	public function send($subject,$template, $to, $reply_email, $from_user) {

		$this->setParams($subject,$template, $to, $reply_email, $from_user);

		$this->preSendExt($to);

		if(count($this->messages) == 0) return false;

		foreach($this->messages as $item) {
				$destination = array('ToAddresses' => array($item['email']));
				$message =  array(
					'Subject' => array(
			            'Data' => $subject,
			            'Charset' => 'UTF-8'
			        ),
			        'Body' => array(
			            'Html' => array(
			                'Data' => $item['body'],
			                'Charset' => 'UTF-8'
			            )
			        )
				);
				$parameters = array(
			        'ReplyToAddresses' => array($reply_email),
			    );
				$response = $this->Amazon->send_email($reply_email, $destination,
					$message, $parameters);

				if(!$response->isOK())
					$this->response[$item['id']] = ++$item['emailErrors'];
		}

		return (count($this->response) == 0);
	}

	public function check_quota($count) {
		$response = $this->Amazon->get_send_quota();
		$quota = $response->body;
		$remain = $quota->GetSendQuotaResult->Max24HourSend - $quota->GetSendQuotaResult->SentLast24Hours;
		return ($count <= $remain);
	}


}

