<?php
class Badges extends CI_Model {
    public function __construct(){
        parent::__construct();
    }

    public function getMyBadges()
    {
        $id = $this->Sess->getChain("id", "user");
        $badges = $this->db->select('user_badges.badgeID as badgeID, user_badges.createdAt as getted, badges.name as name, badges.image as image, badges.description as description')
            ->from('user_badges')
            ->join('badges', 'badges.id = user_badges.badgeID', 'inner')
            ->where('userID', $id)
            ->get()
            ->result_array();
        return $badges;
    }
    public function getBadges($userID)
    {
        $badges = $this->db->select('user_badges.badgeID as badgeID, user_badges.createdAt as getted, badges.name as name, badges.image as image, badges.description as description')
            ->from('user_badges')
            ->join('badges', 'badges.id = user_badges.badgeID', 'inner')
            ->where('userID', $userID)
            ->get()
            ->result_array();
        return $badges;
    }
}