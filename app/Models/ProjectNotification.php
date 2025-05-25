<?php

class ProjectNotification
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
    $stmt = $this->db->prepare("SELECT * FROM project_notifications WHERE id = ?");
    $stmt->execute([$id]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
  }

  public function findUnreadByUser($user_id)
  {
    $stmt = $this->db->prepare("SELECT * FROM project_notifications WHERE user_id = ? AND is_read = 0 ORDER BY created_at DESC");
    $stmt->execute([$user_id]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
  }

  public function findAllByUser($user_id)
  {
    $stmt = $this->db->prepare("SELECT * FROM project_notifications WHERE user_id = ? ORDER BY created_at DESC");
    $stmt->execute([$user_id]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
  }

  public function findByProjectAndUser($project_id, $user_id)
  {
    $stmt = $this->db->prepare("SELECT * FROM project_notifications WHERE project_id = ? AND user_id = ?");
    $stmt->execute([$project_id, $user_id]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
  }

  public function getUnreadByUser($user_id)
  {
    $stmt = $this->db->prepare("
    SELECT 
  MAX(pn.id) as id,
  p.project_name as project_name,
  pn.project_id
FROM project_notifications pn
JOIN projects p ON pn.project_id = p.id
WHERE pn.user_id = :user_id AND pn.is_read = 0
GROUP BY pn.project_id, p.project_name

  ");
    $stmt->execute(['user_id' => $user_id]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
  }


  /**
   * -----------------------
   * CREATE METHOD
   * -----------------------
   */

  public function create($data)
  {
    $stmt = $this->db->prepare("INSERT INTO project_notifications (user_id, project_id, is_read, created_at, updated_at) VALUES (?, ?, 0, NOW(), NOW())");
    return $stmt->execute([
      $data['user_id'],
      $data['project_id'],
    ]);
  }

  /**
   * -----------------------
   * UPDATE METHODS
   * -----------------------
   */

  public function updateUnread($id)
  {
    $stmt = $this->db->prepare("UPDATE project_notifications SET is_read = 0, updated_at = NOW() WHERE id = ?");
    return $stmt->execute([$id]);
  }

  public function markAsRead($id)
  {
    $stmt = $this->db->prepare("UPDATE project_notifications SET is_read = 1, updated_at = NOW() WHERE id = ?");
    return $stmt->execute([$id]);
  }

  public function markAsReadByProjectAndUser($project_id, $user_id)
  {
    $stmt = $this->db->prepare("UPDATE project_notifications SET is_read = 1, updated_at = NOW() WHERE project_id = ? AND user_id = ?");
    return $stmt->execute([$project_id, $user_id]);
  }
}
