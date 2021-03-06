<?php
/* Copyright (c) 1998-2015 ILIAS open source, Extended GPL, see docs/LICENSE */
include_once './Services/Contact/classes/class.ilAbstractMailMemberRoles.php';

/**
 * Class ilMailMemberCourseRoles
 * @author Nadia Matuschek <nmatuschek@databay.de>
 */
class ilMailMemberCourseRoles extends ilAbstractMailMemberRoles
{
	/**
	 * @return string
	 */
	public function getRadioOptionTitle()
	{
		global $lng;
		return $lng->txt('mail_crs_roles');
	}

	/**
	 * @param $ref_id
	 * @return array sorted_roles
	 */
	public function getMailRoles($ref_id)
	{
		global $rbacreview, $lng;
		
		$role_ids = $rbacreview->getLocalRoles($ref_id);

		// Sort by relevance
		$sorted_role_ids = array();
		$counter = 3;
		
		foreach($role_ids as $role_id)
		{
			$role_title = ilObject::_lookupTitle($role_id);
			$mailbox = $this->getMailboxRoleAddress($role_id);
			
			switch(substr($role_title, 0, 8))
			{
				case 'il_crs_a':
					$sorted_role_ids[2]['role_id'] = $role_id;
					$sorted_role_ids[2]['mailbox'] = $mailbox;
					$sorted_role_ids[2]['form_option_title'] = $lng->txt('send_mail_admins');
					break;

				case 'il_crs_t':
					$sorted_role_ids[1]['role_id'] = $role_id;
					$sorted_role_ids[1]['mailbox'] = $mailbox;
					$sorted_role_ids[1]['form_option_title'] = $lng->txt('send_mail_tutors');
					break;

				case 'il_crs_m':
					$sorted_role_ids[0]['role_id'] = $role_id;
					$sorted_role_ids[0]['mailbox'] = $mailbox;
					$sorted_role_ids[0]['form_option_title'] = $lng->txt('send_mail_members');
					break;

				default:
					$str_mail_box = str_replace('<"#','<#',$mailbox);
					$mailbox = str_replace('"@[','@(',$str_mail_box);
					
					$sorted_role_ids[$counter]['role_id'] = $role_id;
					$sorted_role_ids[$counter]['mailbox'] = $mailbox;
					$sorted_role_ids[$counter]['form_option_title'] = $role_title;
					
					$counter++;
					break;
			}
		}
		ksort($sorted_role_ids, SORT_NUMERIC);
		
		return $sorted_role_ids;
	}
}