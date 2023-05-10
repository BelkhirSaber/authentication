<?php 
namespace Kernel\Models;

use Illuminate\Database\Eloquent\Model as Eloquent;
use Illuminate\Database\Eloquent\Relations\BelongsTo;


class UserPermission extends Eloquent{

  protected $table = "users_permissions";
  protected $fillable = [
    'is_admin'
  ];

  public static $default = [
    "is_admin" => false
  ];

  public function user(): BelongsTo{
    return $this->belongsTo(User::class);
  }

}