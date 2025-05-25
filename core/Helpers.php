<?php

function view($view, $data = [])
{
  extract($data);

  $role = $_SESSION['user']['role'] ?? 'guest';

  // Tentukan layout berdasarkan role
  switch ($role) {
    case 'admin':
      $layout = 'layouts/admin.php';
      break;
    case 'mandor':
      $layout = 'layouts/mandor.php';
      break;
    default:
      $layout = 'layouts/app.php'; // untuk guest atau default
      break;
  }

  include __DIR__ . '/../views/' . $layout;
}

function redirect($url)
{
  header("Location: $url");
  exit;
}

function validate($data, $rules)
{
  $errors = [];

  foreach ($rules as $field => $ruleSet) {
    $value = $data[$field] ?? null;

    // Pisahkan aturan jika ada lebih dari satu
    $ruleSet = is_array($ruleSet) ? $ruleSet : explode('|', $ruleSet);

    foreach ($ruleSet as $rule) {
      if ($rule === 'required' && (is_null($value) || trim($value) === '')) {
        $errors[$field] = ucfirst($field) . ' is required.';
      } elseif ($rule === 'string' && !is_string($value)) {
        $errors[$field] = ucfirst($field) . ' must be a string.';
      } elseif ($rule === 'numeric' && !is_numeric($value)) {
        $errors[$field] = ucfirst($field) . ' must be a number.';
      } elseif (strpos($rule, 'min:') === 0) {
        $min = (int) str_replace('min:', '', $rule);
        if (strlen($value) < $min) {
          $errors[$field] = ucfirst($field) . " must be at least $min characters.";
        }
      } elseif (strpos($rule, 'max:') === 0) {
        $max = (int) str_replace('max:', '', $rule);
        if (strlen($value) > $max) {
          $errors[$field] = ucfirst($field) . " must not exceed $max characters.";
        }
      } elseif (strpos($rule, 'regex:') === 0) {
        $pattern = str_replace('regex:', '', $rule);
        if (!preg_match($pattern, $value)) {
          $errors[$field] = ucfirst($field) . ' format is invalid.';
        }
      }
    }
  }

  if (!empty($errors)) {
    if (isset($_SERVER['HTTP_ACCEPT']) && strpos($_SERVER['HTTP_ACCEPT'], 'application/json') !== false) {
      header('Content-Type: application/json');
      echo json_encode([
        'status' => 'error',
        'message' => 'Validation failed.',
        'errors' => $errors
      ]);
      exit;
    }

    $_SESSION['alert'] = [
      'type' => 'danger',
      'message' => 'Validation failed.',
    ];
    redirect($_SERVER['HTTP_REFERER']);
    exit;
  }
}
