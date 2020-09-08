<?php
class report extends module {
	public $allowedMethods = [
		'id'=> ['type'=>'get'],
		'reported_user'=> ['type'=>'post'],
		'report_reason'=> ['type'=>'post'],
		'report_text'=> ['type'=>'post'],
		'submit'=> ['type'=>'post'],
	];
	public $pageName = 'Home';
	public $banned_user_lvl = 4;
	public function constructModule() {
		$reasonsQ = $this->db->prepare("SELECT * FROM `user_reports_reasons` ORDER BY `URR_id` ASC;");
		$reasonsQ->execute();
		$reasons=[];
		foreach($reasonsQ->fetchAll(PDO::FETCH_ASSOC) as $data){
			$reasons[] = [
				'id'	=> $data["URR_id"],
				'name'	=> $data["URR_name"],
			];
		}
		if(isset($this->methodData->id) && $this->methodData->id > 0){
			$this->methodData->id = (int) abs(intval($this->methodData->id));
			$u = new User($this->methodData->id);
			$userdata = true;
		}
		elseif(isset($this->methodData->reported_user) && $this->methodData->reported_user > 0){
			$this->methodData->reported_user = (int) abs(intval($this->methodData->reported_user));
			$u = new User($this->methodData->reported_user);
			$userdata = true;
		}
		$this->html .= $this->page->buildElement("reportFormTPL", [
			"reasons" => $reasons,
			"user" => ((isset($userdata))? $u->user : ""),
		]);
	}

	public function method_report() {
		if (isset($this->methodData->submit)) {
			$this->methodData->reported_user = (int) abs(intval($this->methodData->reported_user));
			$u = new User($this->methodData->reported_user);
			if ($u->info->U_id == $this->user->info->U_id) {
				return $this->error("You cant report your self!");
			}
			if (!isset($u->info->U_id)) {
				return $this->error("This user does not exist!");
			}
			if ($u->info->U_userLevel == $this->banned_user_lvl) {
				return $this->error("This user is already banned!");
			}
			
			$reported = $this->db->prepare("SELECT * FROM `user_reports` WHERE `UR_reported_by` = :by AND `UR_reported_user` = :user ;");
			$reported->bindParam(':by', $this->user->id);
			$reported->bindParam(':user', $this->methodData->reported_user);
			$reported->execute();
			if ($reported->rowCount()>0) {
				return $this->error("You already reported this player!");
			}
			if (!$this->user->checkTimer("reportTimer")) {
				$time = $this->user->getTimer('reportTimer');
				$error = array(
					"timer" => "reportTimer",
					"text"	=>'You cant report again untill your timer is up!',
					"time"	=> $this->user->getTimer("reportTimer")
				);
				$this->html .= $this->page->buildElement('timer', $error);
			}else{
				$insert = $this->db->prepare("
					INSERT INTO `user_reports` (
						UR_reported_by, 
						UR_reported_user, 
						UR_report_text, 
						UR_report_reason,
						UR_date
					) VALUES (
						:by, 
						:user, 
						:text, 
						:reason,
						:date
					);
				");
				$time = time();
				$insert->bindParam(":by", $this->user->id);
				$insert->bindParam(":user", $this->methodData->reported_user);
				$insert->bindParam(":text", $this->methodData->report_text);
				$insert->bindParam(":reason", $this->methodData->report_reason);
				$insert->bindParam(":date", $time);
				$insert->execute();

				$this->user->updateTimer("reportTimer", 60, true);
				$this->html .= $this->page->buildElement("success", array(
					"text" => "This user has been reported."
				));
			}
		}
	}
}