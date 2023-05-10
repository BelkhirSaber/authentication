<?php 

namespace Kernel\Helpers;

// use kernel\User\User;

class Validator{

  private $errors;
  private $rules_errors;
  private  $data;
  private $validation;

  // Construct
  public function __construct(){
    define('MAX_FLAG', 'max');
    define('MIN_FLAG', 'min');
    $this->errors = [];
    $this->rules_errors = [];
    $this->validation = false;
  }


  // Make
  public function make(array $data, array $rules = array(), array $messages = array()){
    if(empty($this->verifyAllRules($rules))){
      $this->validate($data, $rules, $messages);
      return ;
    }
    return $this->verifyAllRules($rules);
  }

  /**
   * Fails
   */
  public function fails(){
    return !empty($this->errors);
  }

  /**
   * Get the message errors
   */
  public function errors(){
    return $this->errors;
  }


  /**
   * Check if data fields is valid
   */

  protected function validate(array $data, array $rules, array $messages){
    $this->data = $data;
    $rules = $this->convertStringToArrayRule($rules);
    foreach($data as $key => $value){
      foreach($rules[$key] as $rule){
        $this->applyRule($rule, $key, $value, $messages);
      }
    }
  }

  /**
   * Apply rule function
   */
  protected function applyRule($rule, $field, $value, $messages){
    switch($rule){
      case str_contains($rule, 'required'):
        !$this->required($value)? 
        $this->addError($this->errors, $field, 'required', $messages[$field.'.required']) : '';
        break;
      case str_contains($rule, 'unique'):
        if(($field == 'email' && $this->isEmail($value)) || ($field == 'username' && $this->validUsername($value))){
          $options = $this->getRuleOption($rule);
          !$this->unique($options['table'], $field, $value)? 
          $this->addError($this->errors, $field, 'unique', $messages[$field . '.unique']) : '';
        }
        break;
      case str_contains($rule, 'min'):
        if($this->required($value)){
          $length = $this->getRuleOption($rule)['option'];
          !$this->min($value, $length)?
          $this->addError($this->errors, $field, 'min', $messages[$field . '.min']) : '';
        }
        break;
      case str_contains($rule, 'max'):
        if($this->required($value)){
          $length = $this->getRuleOption($rule)['option'];
          !$this->max($value, $length)?
          $this->addError($this->errors, $field, 'max', $messages[$field . '.max']) : '';
        }
        break;
      case str_contains($rule, 'email'):
        if($this->required($value)){
          !$this->isEmail($value)? 
          $this->addError($this->errors, $field, 'email', $messages[$field . '.email']) : '';
        }
        break;
      case str_contains($rule, 'username'):
        if($this->required($value)){
          !$this->validUsername($value)?
          $this->addError($this->errors, $field, 'username', $messages[$field . '.username']) : '';
        }
        break;
      case str_contains($rule, 'matches'):
        if($this->required($value)){
          $match = $this->data[$this->getRuleOption($rule)['field']];
          !$this->matches($value, $match) ?
          $this->addError($this->errors, $field, 'matches', $messages[$field . '.matches']) : '';
        }
        break;
      case str_contains($rule, 'strong'):
        if($this->required($value)){
          !$this->strongPass($value) ? 
          $this->addError($this->errors, $field, 'strong', $messages[$field . '.strong']) : '';
        }
        break;
      case str_contains($rule, 'identifier'):
        if($this->required($value)){
          !$this->identifier($value) ? 
          $this->addError($this->errors, $field, 'identifier', $messages[$field . '.identifier']) : '';
        }
        break;
      default:
        break;
    }
  }

  /**
   * Get option from a valid rule f exists
   * @param string
   */
  protected function getRuleOption($rule){
    $result = [];
    switch($rule){
      case str_contains($rule, ',') && str_contains($rule, ':'):
        $aux = explode(',', $rule); $result['field'] = array_pop($aux);
        $aux = explode(':', array_shift($aux)); $result['table'] = array_pop($aux);
        break;
      case str_contains($rule, ':'):
        $aux = explode(':', $rule); $result['option'] = array_pop($aux);
        break;
      case str_contains($rule, '('):
        $result['field'] = substr($rule, strpos($rule, '(') + 1, strpos($rule, ')') - strpos($rule, '(') - 1);
        break;
      default:
        $result = [];
        break;
    }
    return $result;
  }

  /**
   * verify all rules
   */
  protected function verifyAllRules(array $rules){
    foreach($this->convertStringToArrayRule($rules) as $key => $rule){
      foreach($rule as $r){
        if(!$this->isValidRule($r)){
          $this->addError($this->rules_errors, $key, $r);
        }
      }
    }
    return $this->rules_errors;
  }

  /**
   * Convert a string rule to array rule
   */

  protected function convertStringToArrayRule(array $rules){
    foreach($rules as $key => $rule){
      $rules[$key] = explode('|', $rule);
    }
    return $rules;
  }

  /**
   * Check if a given rule is valid
   * @param string
   */
  protected function isValidRule($rule){
    $patterns = ['/^([A-Za-z]+)$/', '/^[A-Za-z]+:\d+$/', '/^[A-Za-z]+:[A-Za-z]+,[A-Za-z]+$/', '/^[A-Za-z]+\([A-Za-z]+[_\-]?[A-Za-z]+\)$/'];
    foreach($patterns as $pattern){
      if(preg_match($pattern, $rule)) return true;
    }    
    return false;
  }

  /**
   * Add error
   */
  protected function addError(&$array_errors, $key, $rule, $message = ""){
    if($message == ""){
      $array_errors[$key . "." . $rule] = !empty($rule) ? 'invalid syntax rule' : 'empty rule "||" not accepted';
    }
    $array_errors[$key . "." . $rule] = $message;
  }

  /**
   * Check if field is unique
   */
  protected function unique($model, $field, $value){
    $model = "kernel\Models\\" . ucfirst($model);
    $instance = new $model();
    $row = $instance->where($field, trim($value))->first();
    return empty($row->$field) ? true : false;
  }

  /**
   * Check if field is required
   */
  protected function required($value){
    return !empty(trim($value));
  }

  /**
   * Check min length
   */
  protected function min($value, $length){
    return (strlen(trim($value)) >= $length);
  }

  /**
   * Check max length
   */
  protected function max($value, $length){
    return (strlen(trim($value)) <= $length);
  }

  /**
   * Matches both value
   * @param string $value
   * @param string $match
   */
  protected function matches($value, $match){
    return trim($value) == trim($match) ;
  }

  /**
   * Check if valid email
   * @param string $email
   */
  protected function isEmail($email) {
    return (bool) filter_var(trim($email), FILTER_VALIDATE_EMAIL);
  }

  /**
   * Check if valid username
   * @param string $str
   */
  protected function validUsername($value){
    return (bool) preg_match('/(?!.*[\.\-\_]{2,})^[a-zA-Z0-9\.\-\_]{3,24}$/', trim($value));
  }

  /**
   * Check if is a strong password
   */
  protected function strongPass($value){
    return (bool) preg_match('/((?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[\W]).{8,64})/', $value);
  }

  /**
   * Identifier
   */

  public function identifier($value){
    return (bool) $this->isEmail($value) ?: $this->validUsername($value);
  }

}//end of class