<?php 
namespace Kernel\Models;

use Illuminate\Database\Eloquent\Model as Eloquent;
use Illuminate\Database\Eloquent\Relations\HasOne;

class User extends Eloquent{

  protected $table = 'users';
  protected $fillable = [
    'email',
    'username',
    'password',
    'active',
    'active_hash',
    'recover_hash',
    'remember_identifier',
    'remember_token',
    'confirm_code'
  ];

  public function getFullName(){
    if(!$this->first_name || !$this->last_name){
      return null;
    }
    return "$this->first_name $this->last_name";
  }

  public function getUsernameOrFullName(){
    return $this->getFullName() ?: $this->username;
  }

  public function getAvatar($options = []){
    $size = isset($options['size']) ? $options['size'] : 25;
    return "https://www.gravatar.com/avatar/" . md5($this->email) . "?s=" . $size . '&d=mp';
  }

  // updateRememberCredentials()
  public function updateRememberCredentials($identifier, $token){
    $this->update([
      'remember_identifier' => $identifier,
      'remember_token' => $token
    ]);
  }

  public function removeRememberCredentials(){
    $this->updateRememberCredentials(null, null);
  }

  // Update recover hash
  public function updateRecoverHash($recover){
    $this->update([
      'recover_hash' => $recover
    ]);
  }

  // Permissions relation
  public function permission(): HasOne{
    return $this->hasOne(UserPermission::class, 'user_id', 'id');
  }

  public function hasPermission($permission){
    return (bool) $this->permission->{$permission};
  }

  public function isAdmin(){
    return $this->hasPermission('is_admin');
  }

}