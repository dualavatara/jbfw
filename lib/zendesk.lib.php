<?php
/**
 * Created by JetBrains PhpStorm.
 * User: g.sokolik
 * Date: 14.09.11
 * Time: 18:52
 */

require_once ('thirdpaty/zendesk.thirdpaty.php');

class ZendeskException extends Exception {}

class ZendeskLib {

	const NO_USER_ERROR = 0x0001;

	private $zd;

	public static $out = null;

	public function __construct($output = ZENDESK_OUTPUT_JSON) {
		$this->zd = new Zendesk(ZD_ACCOUNT, ZD_USERNAME, ZD_PASSWORD);
		$this->zd->set_output($output);
		$this->out = $this;
	}

	public static function getObj($output = ZENDESK_OUTPUT_JSON) {
		if (self::$out == null) self::$out = new ZendeskLib($output);
		return self::$out;
	}

	public function getFAQ($forumId) {
		$faq = json_decode($this->zd->get(ZENDESK_FORUMS, array('id' => $forumId.'/entries')));
		if ($faq->error) return null;
		return $faq;
	}

	public function getTickets($user, $consumerKey = '') {
		if ($user != null) {
			$zdUser = $this->getUser($user['zd_email']);
			if ($zdUser == null) return null;
			$result = json_decode($this->zd->get(ZENDESK_SEARCH, array('query' => 'query=type:ticket+requester_id:'.$zdUser[0]->id.'+order_by:created_at+sort:desc')));
			if ($result->error) return null;
			return $result;
		}
		$result = json_decode($this->zd->get(ZENDESK_RULES, array('id' => ZD_RULE_ID)));
		if ($result->error) return null;
		return $result;
	}

	public function getUser($email) {
		$result = json_decode($this->zd->get(ZENDESK_USERS, array('query' => 'query='.$email)));
		if ($result->error) return null;
		return $result;
	}

	public function setUser($user) {
		return $this->zd->create(ZENDESK_USERS, $user);
	}

	public function setTicket($ticket) {
		if (isset($ticket['comments'])) {
			$comments = $ticket['comments'];
			unset($ticket['comments']);
		}
		$id = $this->zd->create(ZENDESK_TICKETS, array('details' => $ticket));
		foreach ($comments as $comment)
			$this->setComment($id, $comment['value'], $ticket['requester-email']);
		return $id;
	}

	public function setFAQ($faq) {
		return $this->zd->create(ZENDESK_ENTRIES, $faq);
	}

	public function getTicket($ticketId) {
		$result = $this->zd->get(ZENDESK_TICKETS, array('id' => $ticketId));
		if ($result->error) return null;
		return $result;
	}

	public function setComment($ticketId, $comment, $email) {
		$user = $this->getUser($email);
		if (!$user) throw new ZendeskException('No user with this email founded');
		$ticket = $this->getTicket($ticketId);
		if (!$ticket) throw new ZendeskException('No ticket with this ticketId founded');
		if ($ticket->status_id == ZENDESK_TICKET_CLOSED || $ticket->status_id == ZENDESK_TICKET_SOLVED) throw new ZendeskException('This ticket is closed');
		if ($ticket->requester_id != $user['id']) throw new ZendeskException('This ticket is not for this user');
		return $this->zd->update(ZENDESK_TICKETS, array('id' => $ticketId, 'details' => array('value' => $comment)));
	}
}
?>