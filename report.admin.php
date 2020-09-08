<?php
    class adminModule {

        public $banned_user_lvl = 4;
        public function method_reports() {
			$ReportsQ = $this->db->prepare("SELECT * FROM `user_reports` ORDER BY `UR_id` DESC ;");
			$ReportsQ->execute();
			$Reports=[];
			foreach($ReportsQ->fetchAll(PDO::FETCH_ASSOC) as $data){
			$reasonsQ = $this->db->prepare("SELECT * FROM `user_reports_reasons` WHERE `URR_id`= :id;");
			$reasonsQ->bindParam(':id', $data['UR_report_reason']);
			$reasonsQ->execute();
			
			$reason = $reasonsQ->fetch(PDO::FETCH_ASSOC);
				$user = new User($data['UR_reported_user']);
				$by = new User($data['UR_reported_by']);
				$Reports[] = [
					'id'=> $data['UR_id'],
					'user'=> $user->user,
					'by'=> $by->user,
					'text'=> $data['UR_report_text'],
					'reason'=> $reason["URR_name"],
				];
			}
			
            $output = array(
                "Reports" => $Reports,
            );
            $this->html .= $this->page->buildElement("reportList", $output);
        }
		
	
        public function method_ban () {
			$this->methodData->id = (int) abs(intval($this->methodData->id));
            if (!isset($this->methodData->id) || $this->methodData->id < 1) {
				return $this->html = $this->page->buildElement("error", array(
					"text" => "No ID specified!"
				));
            }
			
			$exist = $this->db->prepare("SELECT * FROM `user_reports` WHERE `UR_id` = :id LIMIT 1 ;");
			$exist->bindParam(':id', $this->methodData->id);
			$exist->execute();
			if (!$exist->rowCount()) {
				return $this->html .= $this->page->buildElement("error", array(
					"text" => "This Report does not exist!"
				));
			}
			$data = $exist->fetch(PDO::FETCH_ASSOC);
			$u = new User($data['UR_reported_user']);
			if (isset($this->methodData->commit)) {
				if ($u->info->U_id == $this->user->info->U_id) {
					
					## not sure what to do here what ur input in this?
					
					// $delete = $this->db->prepare("DELETE FROM `user_reports` WHERE `UR_id` = :id;");
					// $delete->bindParam(":id", $this->methodData->id);
					// $delete->execute();
					
					return $this->html = $this->page->buildElement("error", array(
						"text" => "You cant ban your self!"
					));
				}
				if (!isset($u->info->U_id)) {
					return $this->html = $this->page->buildElement("error", array(
						"text" => "This user does not exist!"
					));
				}
				if ($u->info->U_userLevel == $this->banned_user_lvl) {
					return $this->html = $this->page->buildElement("error", array(
						"text" => "This user is already banned!"
					));
				}
				$u->set("U_userLevel", $this->banned_user_lvl);
				$delete = $this->db->prepare("DELETE FROM `user_reports` WHERE `UR_id` = :id;");
				$delete->bindParam(":id", $this->methodData->id);
				$delete->execute();
				return $this->html = $this->page->buildElement("success", array(
						"text" => "This user <b>\"". $u->info->U_name ."\"</b> is now banned!"
				));
			}
			$output['id'] = $this->methodData->id;
			$output['user'] = $u->user;
			$this->html .= $this->page->buildElement("userBan", $output);
        }
        public function method_Reasons() {
			if (isset($this->methodData->submit)) {
				if(!empty($this->methodData->name)){
					$exist = $this->db->prepare("SELECT * FROM `user_reports_reasons` WHERE `URR_name` = :name LIMIT 1 ;");
					$exist->bindParam(':name', $this->methodData->name);
					$exist->execute();
					if ($exist->rowCount()>0) {
						$this->html .= $this->page->buildElement("error", array(
							"text" => "This reason <b>\"".$this->methodData->name."\"</b> exist already!"
						));
					}else{
						$insert = $this->db->prepare("INSERT INTO `user_reports_reasons` (URR_name) VALUES (:name);");
						$insert->bindParam(":name", $this->methodData->name);
						$insert->execute();
						$this->html .= $this->page->buildElement("success", array(
							"text" => "This Report reason has been created!"
						));
					}
				}
			}
			$reasonsQ = $this->db->prepare("SELECT * FROM `user_reports_reasons` order by `URR_id` ASC;");
			$reasonsQ->execute();
			foreach($reasonsQ->fetchAll(PDO::FETCH_ASSOC) as $data){
				$Reasons[] = [
					'id'=> $data['URR_id'],
					'name'=> $data['URR_name'],
				];
			}
            $output = array(
                "Reasons" => $Reasons,
            );
            $this->html .= $this->page->buildElement("reasonsList", $output);
        }
		
	
		
		
        public function method_deleteReason () {

            if (!isset($this->methodData->id) || $this->methodData->id < 1) {
                return $this->html = $this->page->buildElement("error", array(
                    "text" => "No ID specified"
                ));
            }
			$exist = $this->db->prepare("SELECT * FROM `user_reports_reasons` WHERE `URR_id` = :id LIMIT 1 ;");
			$exist->bindParam(':id', $this->methodData->id);
			$exist->execute();
			$data = $exist->fetch(PDO::FETCH_ASSOC);
			$output['name'] = $data['URR_name'];
			$output['id'] = $data['URR_id'];
			if (!$exist->rowCount()>0) {
                return $this->html = $this->page->buildElement("error", array(
                    "text" => "This Report Reason does not exist"
                ));
            }
            if (isset($this->methodData->commit)) {
                $delete = $this->db->prepare("DELETE FROM `user_reports_reasons` WHERE `URR_id` = :id;");
                $delete->bindParam(":id", $this->methodData->id);
                $delete->execute();
                header("Location: ?page=admin&module=report&action=Reasons");
            }

            $this->html .= $this->page->buildElement("reasonDelete", $output);
        }
    }