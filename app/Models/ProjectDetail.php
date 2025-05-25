<?php

class ProjectDetail
{
  private $db;

  public function __construct()
  {
    $this->db = Connection::getInstance();
  }

  /**
   * -----------------------
   * FIND METHODS
   * -----------------------
   */

  public function find($id)
  {
    $stmt = $this->db->prepare("SELECT * FROM project_details WHERE id = ?");
    $stmt->execute([$id]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
  }

  public function findByProject($project_id)
  {
    $stmt = $this->db->prepare("SELECT * FROM project_details WHERE project_id = ? ORDER BY created_at DESC LIMIT 1");
    $stmt->execute([$project_id]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
  }

  /**
   * -----------------------
   * CREATE METHOD
   * -----------------------
   */

  public function create($data)
  {
    $stmt = $this->db->prepare("INSERT INTO project_details (project_id, user_id, comment, created_at, updated_at) VALUES (?, ?, ?, NOW(), NOW())");
    return $stmt->execute([
      $data['project_id'],
      $data['user_id'],
      $data['comment']
    ]);
  }

  public function updateComment($project_id, $user_id, $comment)
  {
    $stmt = $this->db->prepare("UPDATE project_details SET comment = ?, updated_at = NOW() WHERE project_id = ? AND user_id = ?");
    return $stmt->execute([$comment, $project_id, $user_id]);
  }
}
