<?php

function login($user)
{
  $_SESSION['user'] = [
    'id' => $user['id'],
    'username' => $user['username'],
    'role' => $user['role'],
  ];
}

function logout()
{
  unset($_SESSION['user']);
  unset($_SESSION['csrf_token']);
}

function user()
{
  return $_SESSION['user'] ?? null;
}

function isGuest()
{
  return !isset($_SESSION['user']);
}

function isRole($role)
{
  return isset($_SESSION['user']) && $_SESSION['user']['role'] === $role;
}
